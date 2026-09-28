=== Codeally Media Sanitizer & WebP Tweaks ===
Contributors: oldrup
Tags: media, webp, performance, sanitization, image-optimization
Requires at least: 7.1
Tested up to: 7.1
Requires PHP: 8.2
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sanitizes upload filenames with Danish support, sets WebP quality to 70, purges scaled originals, and aligns MIME types.

== Description ==
Codeally Media Sanitizer & WebP Tweaks optimizes WordPress media handling by sanitizing uploaded filenames with full Danish character support, preserving human-readable Media Library post titles, enforcing WebP quality at 70%, purging raw unscaled files (>2560px), and synchronizing database MIME types when images convert to WebP.

Developed by [Bjarne Oldrup](https://oldrup.dk/) and sponsored by [Codeally](https://codeally.dk/).
Filename sanitization character mapping adapted and expanded from [Clean Image Filenames](https://wordpress.org/plugins/clean-image-filenames/).

== Frequently Asked Questions ==

=== Why should I use Codeally Media Sanitizer & WebP Tweaks? ===
- **Clean & Safe URLs:** Special characters (like Danish æ, ø, å) and spaces in raw filenames create messy percent-encoded URLs and potential server path issues.
- **Readable Media Titles:** Standard filename cleaning alters the visible attachment title; this plugin preserves the original human-readable string as the Media Library post title.
- **Storage Preservation:** Automatically deletes the oversized original unscaled file (>2560px) once WordPress generates the `-scaled` master image, saving server disk space.
- **WebP Quality Control:** Forces WebP image sub-sizes to process at an optimized baseline quality of 70% using a high-priority filter.
- **Database MIME Alignment:** Ensures `post_mime_type` in `wp_posts` is updated to `image/webp` when the physical file on disk is converted to `.webp`.

=== Who is this plugin intended for? ===
This plugin provides a lightweight, minimalist execution pipeline with zero admin interface bloat. It is designed for experienced WordPress users and site builders who understand the WordPress uploads structure, know how thumbnail regeneration behaves, and maintain working site backups.

=== How do I set up and configure the plugin? ===
1. Install and activate the official [Modern Image Formats](https://wordpress.org/plugins/webp-uploads/) plugin by the WordPress Performance Team.
2. Go to **Settings > Media** and change the target image format to **WebP**.
3. Activate this plugin (or import the code into the Code Snippets plugin).
4. Upload media through **Media > Add New** or directly inside any Block Editor image block. Filenames automatically sanitize, titles remain readable, unscaled original files (>2560px) auto-delete post-scaling, and database MIME types align to WebP.

=== Can I use this plugin to convert existing media to WebP? ===
Not on its own. This plugin focuses on optimizing incoming uploads, filename sanitization, WebP quality limits, and storage cleanup. It does not contain a background database scanner or bulk converter. However, you can convert existing attachments by pairing this plugin stack with a thumbnail regeneration tool (such as *Force Regenerate Thumbnails*).

=== How does this work together with thumbnail regeneration plugins? ===
When you run a tool like *Force Regenerate Thumbnails* with *Modern Image Formats* active, WordPress rebuilds all sub-size images.
- **If "Output fallback images" is DISABLED:** The regeneration process deletes older `.jpg` sub-sizes and creates *only* `.webp` versions. If existing posts contain hardcoded `<img>` tags pointing to `.jpg` files, those image links will 404.
- **If "Output fallback images" is ENABLED:** WordPress creates both `.jpg` and `.webp` sub-sizes. Existing post image links pointing to `.jpg` continue to resolve, while modern browsers automatically request the new `.webp` versions.

=== Should I go "all-in" on WebP without fallback images? ===
WebP is natively supported by over 98% of modern web browsers across desktop and mobile devices. Going "pure WebP" (disabling fallback JPEGs) offers maximum server storage savings.
- **For brand-new websites:** Going pure WebP is highly recommended.
- **For existing websites with legacy content:** Unless you plan to run a database search-and-replace to update `.jpg` URLs to `.webp` in `post_content`, keep **"Output fallback images"** checked under **Settings > Media** to maintain total backwards compatibility for older posts.

=== What free alternatives exist for bulk converting legacy images on existing sites? ===
If you prefer a 1-click bulk conversion tool that preserves original files without needing manual database edits, consider these free, open-source plugins from WordPress.org:
- **EWWW Image Optimizer:** Offers unlimited free local WebP conversion without credit limits, serving WebP via `<picture>` rewrite rules while leaving original files untouched.
- **Converter for Media:** Free 1-click bulk conversion for WebP, storing optimized files separately in `/uploads-webpc/` to avoid breaking existing post references.
- **WebP Express:** A 100% free and open-source plugin with no commercial upsells, serving WebP dynamically via `.htaccess` or server rewrite rules.

=== How does this plugin work with form plugins like WS Form? ===
Filename sanitization is intentionally bypassed for form uploads to respect custom form naming rules and variables. However, WebP conversion, 70% quality limits, and purging of oversized raw originals (>2560px) still execute automatically.
- **Note:** This behavior has been lightly verified with WS Form Pro, but has not been tested with other form plugins.

=== Where can I find official documentation and references? ===
- [WordPress Performance Team: Modern Image Formats Plugin](https://wordpress.org/plugins/webp-uploads/)
- [WordPress Developer Reference: wp_handle_upload_prefilter](https://developer.wordpress.org/reference/hooks/wp_handle_upload_prefilter/)
- [WordPress Core Notes: Handling of Big Images in WP 5.3+](https://make.wordpress.org/core/2019/10/09/introducing-handling-of-big-images-in-wordpress-5-3/)

== Installation ==
1. Upload the snippet to your WordPress `/wp-content/plugins/` directory, or import into the Code Snippets plugin.
2. Activate the plugin/snippet through the WordPress admin.

== Changelog ==
= 1.1.0 =
- Added defensive `wp-admin/includes/file.php` inclusion before purging unscaled raw originals to prevent REST/front-end execution crashes.
- Replaced database transients with a zero-DB in-memory lookup table (`$GLOBALS`) for title retention and bulk-upload safety.
- Converted character sanitization lookup array to static memory allocation for optimized execution speed.
- Added FAQ section describing form plugin behavior and light compatibility verification with WS Form Pro.

= 1.0.3 =
- Updated FAQ recommendations to highlight free and open-source bulk image optimization plugins.

= 1.0.2 =
- Expanded FAQ documentation regarding thumbnail regeneration, fallback image behavior, and existing media workflows.

= 1.0.1 =
- Added inline and readme attribution credit for Clean Image Filenames.

= 1.0.0 =
- Initial release.