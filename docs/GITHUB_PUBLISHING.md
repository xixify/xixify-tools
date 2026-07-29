# GitHub Publishing Guide

This guide explains how to package and publish tools from the `xixify tools` repository to GitHub as separate releases or sub-modules.

---

## 1. Repository Publishing Options

### Option A: Monorepo Distribution (Recommended Initial Setup)
Keep all Xixify tools together in the `xixify-tools` GitHub repository:
- Each tool lives under `tools/<category>/<tool-name>`.
- Users can clone the repository or download specific tool zips from GitHub Releases.

### Option B: Individual Tool Repository
For major tools (such as full WordPress plugins):
1. Initialize a new standalone GitHub repository (e.g. `xixify-image-compressor`).
2. Copy the tool folder into the repo root.
3. Push to GitHub.

---

## 2. Preparing a Tool for Release

Before publishing a tool or creating a release tag:

1. **Verify Metadata**:
   - Ensure the version number is updated in `package.json`, WP plugin header, or tool configuration.
   - Update `CHANGELOG.md` or `readme.txt` with release notes.

2. **Zip Packaging**:
   For WordPress plugins or downloadable tools, create a clean zip file excluding git history or build dependencies:
   ```bash
   zip -r xixify-tool-v1.0.0.zip tools/wordpress-plugins/my-plugin -x "*.git*"
   ```

3. **Release Tagging**:
   - Push release tag (e.g., `v1.0.0`) to GitHub.
   - Create a GitHub Release attaching the release zip and detailing key feature updates.
