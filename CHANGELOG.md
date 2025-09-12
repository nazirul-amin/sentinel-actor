# Changelog

All notable changes to `sentinel-actor` will be documented in this file.

## v1.0.0 - 2025-09-12

### Added

-   Initial release of Sentinel Actor monitoring package
-   Exception monitoring for Laravel jobs and notifications
-   Manual exception and event reporting capabilities
-   HMAC-SHA256 signature verification for secure webhook communication

### Changed

-   Updated PHP compatibility to ^8.1 (from ^8.4)
-   Updated Laravel compatibility to ^10.0||^11.0||^12.0 (from ^11.0||^12.0)
-   Updated GitHub Actions workflows to test against broader PHP and Laravel versions
-   Added CONTRIBUTING.md file
-   Fixed publishable tag names to use consistent "sentinel-actor" prefix
