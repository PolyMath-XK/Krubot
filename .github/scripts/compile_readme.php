<?php // CRITICAL: Start with <?php tag inside the Heredoc to ensure PHP parsing context.
/**
 * @author: Pro-Gemini-XTR 
 * @description: Orchestrates the dimensional merging of Markdown fragments into a unified README.md.
 *              This script embodies HyperDX principles by being self-contained,
 *              efficient, and directly leveraging PHP 8.2.30 capabilities.
 *              It aligns with ECMA2026 principles of modularity and clear intent.
 *              Handles dynamic inclusion of Markdown files based on a specific pattern.
*/

// Define constants for file paths, enhancing maintainability and clarity (HyperDX).
define("README_TEMPLATE_PATH", "README.mdx");
define("README_OUTPUT_PATH", "README.md");

// Regex pattern for inclusion directives: <!-- INCLUDE: path/to/file.md -->
// (?<file>[^\\s]+(?:\\.md|\\.MD)) captures the file path into a named group "file",
// ensuring only .md or .MD files are considered, adding robustness.
define("INCLUDE_PATTERN", "/<!--\\s*INCLUDE:\\s*(?<file>[^\\s]+(?:\\.md|\\.MD))\\s*-->/");

$templateContent = "";
// ROBUSTNESS: Critical check to ensure the template file actually exists.
// If not, it logs a critical error and exits, preventing further failures.
if (!file_exists(README_TEMPLATE_PATH)) {
    error_log("[CRITICAL] Template file not found: " . README_TEMPLATE_PATH . ". Aborting manifestation.");
    exit(1); 
}
$templateContent = file_get_contents(README_TEMPLATE_PATH);

// Core Logic: Perform the transclusion (inclusion) using a callback function for each regex match.
// preg_replace_callback is highly efficient for this task in PHP.
$compiledContent = preg_replace_callback(
    INCLUDE_PATTERN,
    function ($matches) {
        $targetFile = trim($matches["file"]); // Extract the file path from the regex match.
        
        // SECURITY & ROBUSTNESS: Define an explicit whitelist of allowed files/paths for inclusion.
        // This prevents arbitrary file inclusion (e.g., sensitive files like .env or system files)
        // and enforces a clear architectural boundary. This is a CRITICAL PolyMath-level control.
        $allowedPaths = [
            "Code_Showcase.md", // Explicitly allow Code_Showcase.md from the root.
            // Add other specific files or prefixes for directories here, e.g.:
            // "src/Nexus/NoxiousSamples.md",
            // "docs/chapters/", // For files starting with "docs/chapters/"
        ];
        
        // Check if the target file is in the whitelist or starts with an allowed directory prefix.
        $isAllowed = false;
        foreach ($allowedPaths as $allowedPath) {
            if ($targetFile === $allowedPath || str_starts_with($targetFile, $allowedPath)) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            error_log("[SECURITY WARNING] Attempted to include unauthorized file: {$targetFile}. Skipping.");
            return "> 🚫 Security Violation: Inclusion of `{$targetFile}` is not permitted by Krubot Nexus policy.";
        }

        // ROBUSTNESS: Check if the actual target file exists on disk.
        if (file_exists($targetFile)) {
            echo "[INFO] Assimilating dimension: {$targetFile}\n"; // Log successful inclusion for HyperDX.
            return file_get_contents($targetFile); // Return the content of the included file.
        }
        // ROBUSTNESS: If the included file is not found, log a warning and insert a clear placeholder.
        error_log("[WARNING] Broken meta-link: {$targetFile} not found. Placeholder inserted.");
        return "> ⚠️ Meta-Link Broken: `{$targetFile}` not found in this reality. Please rectify the dimensional coordinates.";
    },
    $templateContent
);

// ROBUSTNESS: Check for potential regex errors during the callback process.
if ($compiledContent === null) {
    error_log("[CRITICAL] Regex callback failed. Check pattern or content for syntax errors.");
    exit(1); 
}

// HYPER-PERFORMANCE OPTIMIZATION: Compare compiled content with the existing README.md.
// This is a key performance and "clean history" feature. If the new compiled content
// is IDENTICAL to the currently existing README.md, there's no need to write the file
// or create a new Git commit. This prevents unnecessary file I/O and empty commits.
$existingReadmeContent = file_exists(README_OUTPUT_PATH) ? file_get_contents(README_OUTPUT_PATH) : "";
if ($existingReadmeContent === $compiledContent) {
    echo "[INFO] README.md is already perfectly aligned. No content changes detected. 🧘\n";
    // Exit successfully here. The next 'git diff' step will also confirm no changes.
    exit(0); 
}

// If content has actually changed, write the new compiled content to README.md.
file_put_contents(README_OUTPUT_PATH, $compiledContent);
echo "[SUCCESS] The Ultimate Krubot Lexicon is forged and ready for serve &/ manifestation. 🔮\n";
