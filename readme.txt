=== Codeally Media Sanitizer & WebP Tweaks ===
Contributors: oldrup
Tags: media, webp, performance, sanitization, image-optimization
Requires at least: 7.1
Tested up to: 7.1
Requires PHP: 8.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sanitizes upload filenames with Danish support, sets WebP quality to 70, purges scaled originals, and aligns MIME types.

== Description ==
Codeally Media Sanitizer & WebP Tweaks optimizes WordPress media handling by sanitizing uploaded filenames with full Danish character support, preserving human-readable Media Library post titles, enforcing WebP quality at 70%, purging raw unscaled files (>2560px), and synchronizing database MIME types when images convert to WebP.

Developed by [Bjarne Oldrup](https://oldrup.dk/) and sponsored by [Codeally](https://codeally.dk/).

== Frequently Asked Questions ==

=== Why should I use Codeally Media Sanitizer & WebP Tweaks? ===
- **Clean & Safe URLs:** Special characters (like Danish æ, ø, å) and spaces in raw filenames create messy percent-encoded URLs and potential server path issues.
- **Readable Media Titles:** Standard filename cleaning alters the visible attachment title; this plugin preserves the original human-readable string as the Media Library post title.
- **Storage Preservation:** Automatically deletes the oversized original unscaled file (>2560px) once WordPress generates the `-scaled` master image, saving server disk space.
- **WebP Quality Control:** Forces WebP image sub-sizes to process at an optimized baseline quality of 70% using a high-priority filter.
- **Database MIME Alignment:** Ensures `post_mime_type` in `wp_posts` is updated to `image/webp` when the physical file on disk is converted to `.webp`.

=== How do I set up and configure the plugin? ===
1. Install and activate the official [Modern Image Formats](https://wordpress.org/plugins/webp-uploads/) plugin by the WordPress Performance Team.
2. Go to **Settings > Media** and change the target image format to **WebP**.
3. Activate this plugin (or import the code into the Code Snippets plugin).
4. Upload media through **Media > Add New** or directly inside any Block Editor image block. Filenames automatically sanitize, titles remain readable, unscaled original files (>2560px) auto-delete post-scaling, and database MIME types align to WebP.

=== Where can I find official documentation and references? ===
- [WordPress Performance Team: Modern Image Formats Plugin](https://wordpress.org/plugins/webp-uploads/)
- [WordPress Developer Reference: wp_handle_upload_prefilter](https://developer.wordpress.org/reference/hooks/wp_handle_upload_prefilter/)
- [WordPress Core Notes: Handling of Big Images in WP 5.3+](https://make.wordpress.org/core/2019/10/09/introducing-handling-of-big-images-in-wordpress-5-3/)

== Installation ==
1. Upload the snippet to your WordPress `/wp-content/plugins/` directory, or import into the Code Snippets plugin.
2. Activate the plugin/snippet through the WordPress admin.

== Changelog ==
= 1.0.0 =
- Initial release.