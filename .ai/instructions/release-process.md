# Release Process Guide

## Versioning
Follow Semantic Versioning (`MAJOR.MINOR.PATCH`):
- `MAJOR`: Incompatible API or structural breaking changes.
- `MINOR`: Backward-compatible new features.
- `PATCH`: Backward-compatible bug fixes.

## Release Steps
1. Update version numbers in `package.json`, plugin headers, or script constants.
2. Update `CHANGELOG.md` with release notes.
3. Test production build locally.
4. Tag commit in git: `git tag -a v1.0.0 -m "Release v1.0.0"`.
5. Create GitHub Release and attach production ZIP artifact.
