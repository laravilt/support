# Changelog

All notable changes will be documented in this file.

## 1.0.0-alpha.1 - 2025-01-17

### Added - Phase 1: Core SPA Infrastructure

**JavaScript Core Engine (814 lines)**
- Complete SPA engine (Laravilt.js) with 30+ API methods
- Navigation & page management (visit, replace, refresh)
- Modal & slideover stack management
- Toast notification system
- State persistence (remember/restore/forget)
- Lazy loading & component rehydration
- Confirm dialog system
- Event bus for inter-component communication
- Reactive data access (shared data, flash messages, validation errors)
- File download handling (blob & URL)
- Comprehensive request API with lifecycle hooks
- SSR support

**Vue 3 Integration**
- LaraviltApp.vue (325 lines) - Root application component
  - KeepAlive page caching with configurable max
  - Modal stack rendering with backdrop blur
  - Server error overlay with iframe
  - Meta tag management
  - View Transitions API support
- LaraviltPlugin.js (120 lines) - Vue 3 plugin installer
  - Configurable component prefix
  - Global component registration
  - $laravilt injection
  - Custom component registration
- LaraviltProgress.js (138 lines) - NProgress integration
  - Auto-start on requests
  - Upload/download progress tracking
  - Customizable colors and styling
  - Optional spinner

**Vue Components**
- Link.vue - SPA navigation link
- Modal.vue - Modal/slideover component
- Render.vue (41 lines) - Dynamic HTML renderer
- ComponentRenderer.vue - Dynamic component renderer
- ServerError.vue (68 lines) - Error overlay display

**Build System**
- Vite configuration for library mode
- ES Module output (91.64 KB, 29.13 KB gzipped)
- UMD bundle (71.11 KB, 26.57 KB gzipped)
- Tree-shakeable exports

**Dependencies**
- axios ^1.6.0
- lodash-es ^4.17.21
- nprogress ^0.2.0

**Documentation**
- Updated README with comprehensive SPA documentation
- Enhanced Laravilt Core documentation
- API reference for all 30+ methods
- Vue plugin configuration guide
- Component usage examples

### Status
- **Overall Completion**: 45%
- **Phase 1**: ✅ Complete
- **Phase 2**: ⏳ Pending (Backend Core)
- **Phase 3**: ⏳ Pending (Essential Vue Components)

## 1.0.0 - TBD

- Full release with complete feature parity with Splade
