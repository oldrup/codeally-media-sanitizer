<?php
declare(strict_types=1);

/**
 * Plugin Name:       Codeally Media Sanitizer & WebP Tweaks
 * Plugin URI:        https://github.com/oldrup/codeally-media-sanitizer-webp-tweaks
 * Description:       Sanitizes upload filenames with Danish support, sets WebP quality to 70, purges scaled originals, and aligns MIME types.
 * Version:           1.1.0
 * Requires at least: 7.1
 * Requires PHP:      8.2
 * Author:            Bjarne Oldrup
 * Author URI:        https://oldrup.dk/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       codeally-media-sanitizer-webp-tweaks
 * Tags:              media, webp, performance, sanitization, image-optimization
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ==========================================================================
   1. ACTIVATION NOTICE (ONE-TIME DEPENDENCY REMINDER)
   ========================================================================== */

register_activation_hook( __FILE__, static function() : void {
    set_transient( '_cdly_media_sanitizer_activated', true, 60 );
} );

add_action( 'admin_notices', static function() : void {
    if ( ! get_transient( '_cdly_media_sanitizer_activated' ) ) {
        return;
    }

    delete_transient( '_cdly_media_sanitizer_activated' );

    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    // Check if Modern Image Formats plugin function/class is present
    $mif_active = class_exists( 'WebP_Uploads' ) || defined( 'WEBP_UPLOADS_VERSION' );

    if ( ! $mif_active ) {
        $message = sprintf(
            /* translators: 1: Plugin name, 2: Link to Modern Image Formats plugin */
            __( '%1$s is active. For automatic WebP conversion, ensure the %2$s plugin is installed and configured to WebP under Settings > Media.', 'codeally-media-sanitizer-webp-tweaks' ),
            '<strong>' . esc_html__( 'Codeally Media Sanitizer', 'codeally-media-sanitizer-webp-tweaks' ) . '</strong>',
            '<a href="' . esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=webp-uploads' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'Modern Image Formats', 'codeally-media-sanitizer-webp-tweaks' ) . '</a>'
        );

        wp_admin_notice(
            wp_kses_post( $message ),
            array(
                'type'        => 'info',
                'dismissible' => true,
            )
        );
    }
} );

/* ==========================================================================
   2. FILENAME SANITIZATION & ATTACHMENT TITLE
   ========================================================================== */

add_filter( 'wp_handle_upload_prefilter', 'cdly_clean_uploaded_filename' );
add_filter( 'wp_handle_sideload_prefilter', 'cdly_clean_uploaded_filename' );

/**
 * Prefilter upload array to sanitize filenames and store original title in memory.
 *
 * @param array<string, mixed> $file Uploaded file data.
 * @return array<string, mixed>
 */
function cdly_clean_uploaded_filename( array $file ) : array {
    $original     = pathinfo( $file['name'], PATHINFO_FILENAME );
    $file['name'] = cdly_clean_filename( $file['name'] );

    if ( is_string( $original ) && '' !== $original ) {
        $clean_key = pathinfo( $file['name'], PATHINFO_FILENAME );
        $GLOBALS['cdly_original_titles'][ $clean_key ] = $original;
    }

    return $file;
}

/**
 * Sanitize filename string with character mapping and Danish transliteration.
 *
 * Filename character mapping adapted and expanded from Clean Image Filenames.
 * @link https://wordpress.org/plugins/clean-image-filenames/
 * @license GPL-2.0-or-later
 */
function cdly_clean_filename( string $filename ) : string {
    $info = pathinfo( $filename );
    $name = $info['filename'] ?? '';
    $ext  = $info['extension'] ?? '';

    // Replace spaces with dashes
    $clean = str_replace( ' ', '-', $name );

    // Danish & international character replacements (static array prevents re-allocation on bulk uploads)
    static $specific_replacements = array(
        'Æ' => 'ae', 'æ' => 'ae',
        'Ø' => 'oe', 'ø' => 'oe',
        'Å' => 'aa', 'å' => 'aa',
        'А'=>'a','Ά'=>'a','Á'=>'a','Α'=>'a','Ä'=>'a','Ã'=>'a','Â'=>'a','À'=>'a','α'=>'a','Ą'=>'a','а'=>'a','ά'=>'a',
        'б'=>'b','Б'=>'b','Ć'=>'c','Ç'=>'c','ц'=>'c','Ц'=>'c','Č'=>'c','Ч'=>'ch','χ'=>'ch','ч'=>'ch','Χ'=>'ch',
        'д'=>'d','Ď'=>'d','Д'=>'d','δ'=>'d','Δ'=>'d','Ð'=>'d','ε'=>'e','έ'=>'e','Έ'=>'e','Э'=>'e','Ę'=>'e','Ε'=>'e','э'=>'e','Ê'=>'e',
        'Ě'=>'e','É'=>'e','Е'=>'e','е'=>'e','È'=>'e','Ë'=>'e','Φ'=>'f','Ф'=>'f','φ'=>'f','ф'=>'f','Γ'=>'g','γ'=>'g','ґ'=>'g','Г'=>'g',
        'Ґ'=>'g','г'=>'g','Х'=>'h','х'=>'h','Ή'=>'i','Ί'=>'i','І'=>'i','і'=>'i','ΐ'=>'i','Η'=>'i','Ι'=>'i','η'=>'i','ϊ'=>'i','ι'=>'i',
        'Ï'=>'i','Ì'=>'i','ή'=>'i','Í'=>'i','Î'=>'i','ί'=>'i','Ϊ'=>'i','и'=>'i','И'=>'i','й'=>'j','Й'=>'j','Я'=>'ja','я'=>'ja','ю'=>'ju',
        'Ю'=>'ju','Κ'=>'k','κ'=>'k','к'=>'k','К'=>'k','л'=>'l','Λ'=>'l','λ'=>'l','Ł'=>'l','Л'=>'l','Μ'=>'m','м'=>'m','М'=>'m','μ'=>'m',
        'Ñ'=>'n','ν'=>'n','Ν'=>'n','н'=>'n','Ň'=>'n','Ń'=>'n','Н'=>'n','ώ'=>'o','Ò'=>'o','ό'=>'o','Ő'=>'o','Ώ'=>'o','Õ'=>'o','Ο'=>'o',
        'ο'=>'o','Ό'=>'o','Ω'=>'o','Ó'=>'o','Ö'=>'o','ö'=>'o','О'=>'o','о'=>'o','ω'=>'o','Ô'=>'o','п'=>'p','þ'=>'p','π'=>'p',
        'Π'=>'p','П'=>'p','Þ'=>'p','Ψ'=>'ps','ψ'=>'ps','Р'=>'r','Ř'=>'r','Ρ'=>'r','р'=>'r','ρ'=>'r','С'=>'s','σ'=>'s','Ś'=>'s','ς'=>'s',
        'Σ'=>'s','Š'=>'s','с'=>'s','Ш'=>'sh','ш'=>'sh','щ'=>'shch','Щ'=>'shch','ß'=>'ss','Τ'=>'t','τ'=>'t','Ť'=>'t','т'=>'t','Т'=>'t',
        'θ'=>'th','Θ'=>'th','Ў'=>'u','ў'=>'u','Ű'=>'u','Ú'=>'u','У'=>'u','Ù'=>'u','Û'=>'u','Ů'=>'u','у'=>'u','ü'=>'u','Ü'=>'u','в'=>'v',
        'В'=>'v','Β'=>'v','β'=>'v','Ξ'=>'x','×'=>'x','ξ'=>'x','Ϋ'=>'y','Ÿ'=>'y','Ý'=>'y','Υ'=>'y','υ'=>'y','Ύ'=>'y','ΰ'=>'y',
        'ϋ'=>'y','ы'=>'y','Ы'=>'y','є'=>'ye','Є'=>'ye','ї'=>'yi','Ї'=>'yi','ё'=>'yo','Ё'=>'yo','з'=>'z','Ź'=>'z','З'=>'z','Ž'=>'z',
        'ζ'=>'z','Ζ'=>'z','Ż'=>'z','Ж'=>'zh','ж'=>'zh','_'=>'-','%20'=>'-',
    );

    $clean = str_replace( array_keys( $specific_replacements ), array_values( $specific_replacements ), $clean );
    $clean = remove_accents( $clean );
    $clean = strtolower( $clean );
    $clean = preg_replace( '/[^a-z0-9-]/', '', $clean ) ?? '';
    $clean = preg_replace( '/-+/', '-', $clean ) ?? '';
    $clean = trim( $clean, '-' );

    return $clean . ( $ext ? '.' . $ext : '' );
}

/**
 * Set original, un-sanitized string as attachment title in Media Library.
 */
add_action( 'add_attachment', static function( int $attachment_id ) : void {
    $file = get_attached_file( $attachment_id );

    if ( is_string( $file ) ) {
        $clean_key = pathinfo( $file, PATHINFO_FILENAME );

        if ( isset( $GLOBALS['cdly_original_titles'][ $clean_key ] ) ) {
            wp_update_post( array(
                'ID'         => $attachment_id,
                'post_title' => $GLOBALS['cdly_original_titles'][ $clean_key ],
            ) );

            unset( $GLOBALS['cdly_original_titles'][ $clean_key ] );
        }
    }
} );

/* ==========================================================================
   3. WEBP BASELINE QUALITY (70)
   ========================================================================== */

/**
 * Force WebP editor quality to 70 across all image sub-size operations.
 * Priority set to 99 to run after Modern Image Formats or Core defaults.
 */
add_filter( 'wp_editor_set_quality', static function( int $quality, string $mime_type ) : int {
    if ( 'image/webp' === $mime_type ) {
        return 70;
    }
    return $quality;
}, 99, 2 );

/* ==========================================================================
   4. STORAGE OPTIMIZATION, PURGING & MIME ALIGNMENT
   ========================================================================== */

/**
 * Auto-delete original unscaled raw uploads (>2560px) and align database MIME types.
 */
add_filter( 'wp_generate_attachment_metadata', static function( array $metadata, int $attachment_id ) : array {
    // 1. Purge original unscaled raw uploads (>2560px)
    if ( ! empty( $metadata['original_image'] ) && ! empty( $metadata['file'] ) && is_string( $metadata['file'] ) ) {
        $upload_dir    = wp_upload_dir();
        $file_dir      = pathinfo( $metadata['file'], PATHINFO_DIRNAME );
        $original_path = path_join( $upload_dir['basedir'], path_join( $file_dir, $metadata['original_image'] ) );

        if ( file_exists( $original_path ) && is_file( $original_path ) ) {
            if ( ! function_exists( 'wp_delete_file' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            wp_delete_file( $original_path );
        }

        unset( $metadata['original_image'] );
    }

    // 2. Synchronize post_mime_type in wp_posts if the file on disk is WebP
    $file = get_attached_file( $attachment_id );
    if ( is_string( $file ) && str_ends_with( strtolower( $file ), '.webp' ) ) {
        if ( 'image/webp' !== get_post_mime_type( $attachment_id ) ) {
            wp_update_post( array(
                'ID'             => $attachment_id,
                'post_mime_type' => 'image/webp',
            ) );
        }
    }

    return $metadata;
}, 10, 2 );