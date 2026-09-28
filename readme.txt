=== Codeally Media Sanitizer & WebP Tweaks ===
Contributors: oldrup
Tags: media, webp, performance, sanitization, image-optimization
Requires at least: 7.1
Tested up to: 7.1
Requires PHP: 8.2
Stable tag: 1.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sanitizes upload filenames with Danish support, sets WebP quality to 70, purges scaled originals, and aligns MIME types.

== Description ==
Codeally Media Sanitizer & WebP Tweaks optimizes WordPress media handling by sanitizing uploaded filenames with full Danish character support, preserving human-readable Media Library post titles, enforcing WebP quality at 70%, purging raw unscaled files (>2560px), and synchronizing database MIME types when images convert to WebP[cite: 5].

Developed by [Bjarne Oldrup](https://oldrup.dk/) and sponsored by [Codeally](https://codeally.dk/)[cite: 5].
Filename sanitization character mapping adapted and expanded from [Clean Image Filenames](https://wordpress.org/plugins/clean-image-filenames/)[cite: 5].

== Frequently Asked Questions ==

=== Why should I use Codeally Media Sanitizer & WebP Tweaks? ===
- **Clean & Safe URLs:** Special characters (like Danish æ, ø, å) and spaces in raw filenames create messy percent-encoded URLs and potential server path issues[cite: 5].
- **Readable Media Titles:** Standard filename cleaning alters the visible attachment title; this plugin preserves the original human-readable string as the Media Library post title[cite: 5].
- **Storage Preservation:** Automatically deletes the oversized original unscaled file (>2560px) once WordPress generates the `-scaled` master image, saving server disk space[cite: 5].
- **WebP Quality Control:** Forces WebP image sub-sizes to process at an optimized baseline quality of 70% using a high-priority filter[cite: 5].
- **Database MIME Alignment:** Ensures `post_mime_type` in `wp_posts` is updated to `image/webp` when the physical file on disk is converted to `.webp`[cite: 5].

=== How do I set up and configure the plugin? ===
1. Install and activate the official [Modern Image Formats](https://wordpress.org/plugins/webp-uploads/) plugin by the WordPress Performance Team[cite: 5, 6].
2. Go to **Settings > Media** and change the target image format to **WebP**[cite: 5, 6].
3. Activate this plugin (or import the code into the Code Snippets plugin)[cite: 5, 6].
4. Upload media through **Media > Add New** or directly inside any Block Editor image block[cite: 5, 6]. Filenames automatically sanitize, titles remain readable, unscaled original files (>2560px) auto-delete post-scaling, and database MIME types align to WebP[cite: 5, 6].

=== Can I use this plugin to convert existing media to WebP? ===
Not on its own[cite: 5]. This plugin focuses on optimizing incoming uploads, filename sanitization, WebP quality limits, and storage cleanup[cite: 5]. It does not contain a background database scanner or bulk converter[cite: 5]. However, you can convert existing attachments by pairing this plugin stack with a thumbnail regeneration tool (such as *Force Regenerate Thumbnails*)[cite: 3, 5].

=== How does this work together with thumbnail regeneration plugins? ===
When you run a tool like *Force Regenerate Thumbnails* with *Modern Image Formats* active, WordPress rebuilds all sub-size images[cite: 3].
- **If "Output fallback images" is DISABLED:** The regeneration process deletes older `.jpg` sub-sizes and creates *only* `.webp` versions[cite: 3, 4]. If existing posts contain hardcoded `<img>` tags pointing to `.jpg` files, those image links will 404[cite: 3, 4].
- **If "Output fallback images" is ENABLED:** WordPress creates both `.jpg` and `.webp` sub-sizes[cite: 3, 4]. Existing post image links pointing to `.jpg` continue to resolve, while modern browsers automatically request the new `.webp` versions[cite: 3, 4].

=== Should I go "all-in" on WebP without fallback images? ===
WebP is natively supported by over 98% of modern web browsers across desktop and mobile devices. Going "pure WebP" (disabling fallback JPEGs) offers maximum server storage savings[cite: 3].
- **For brand-new websites:** Going pure WebP is highly recommended[cite: 3].
- **For existing websites with legacy content:** Unless you plan to run a database search-and-replace to update `.jpg` URLs to `.webp` in `post_content`, keep **"Output fallback images"** checked under **Settings > Media** to maintain total backwards compatibility for older posts[cite: 3, 4].

=== What free alternatives exist for bulk converting legacy images on existing sites? ===
If you prefer a 1-click bulk conversion tool that preserves original files without needing manual database edits, consider these free, open-source plugins from WordPress.org:
- **EWWW Image Optimizer:** Offers unlimited free local WebP conversion without credit limits, serving WebP via `<picture>` rewrite rules while leaving original files untouched.
- **Converter for Media:** Free 1-click bulk conversion for WebP, storing optimized files separately in `/uploads-webpc/` to avoid breaking existing post references.
- **WebP Express:** A 100% free and open-source plugin with no commercial upsells, serving WebP dynamically via `.htaccess` or server rewrite rules.

=== Who is this plugin intended for? ===
This plugin provides a lightweight, minimalist execution pipeline with zero admin interface bloat[cite: 5, 6]. It is designed for experienced WordPress users and site builders who understand the WordPress uploads structure, know how thumbnail regeneration behaves, and maintain working site backups[cite: 3, 5].

=== Where can I find official documentation and references? ===
- [WordPress Performance Team: Modern Image Formats Plugin](https://wordpress.org/plugins/webp-uploads/)[cite: 5]
- [WordPress Developer Reference: wp_handle_upload_prefilter](https://developer.wordpress.org/reference/hooks/wp_handle_upload_prefilter/)[cite: 5]
- [WordPress Core Notes: Handling of Big Images in WP 5.3+](https://make.wordpress.org/core/2019/10/09/introducing-handling-of-big-images-in-wordpress-5-3/)[cite: 5]

== Installation ==
1. Upload the snippet to your WordPress `/wp-content/plugins/` directory, or import into the Code Snippets plugin[cite: 5, 6].
2. Activate the plugin/snippet through the WordPress admin[cite: 5, 6].

== Changelog ==
= 1.0.3 =
- Updated FAQ recommendations to highlight free and open-source bulk image optimization plugins (EWWW, Converter for Media, WebP Express) for legacy site conversions.

= 1.0.2 =
- Expanded FAQ documentation regarding thumbnail regeneration, fallback image behavior, and existing media workflows[cite: 3, 5].

= 1.0.1 =
- Added inline and readme attribution credit for Clean Image Filenames[cite: 5].

= 1.0.0 =
- Initial release[cite: 5, 6].