/**
 * Hakai Task Management - Performance Optimizations
 * Contains utility functions for caching, debouncing, and lazy loading
 */

(function (window) {
    'use strict';

    // API Cache with TTL
    const ApiCache = {
        cache: new Map(),
        defaultTTL: 30000, // 30 seconds

        set(key, data, ttl = this.defaultTTL) {
            const expiry = Date.now() + ttl;
            this.cache.set(key, { data, expiry });
        },

        get(key) {
            const item = this.cache.get(key);
            if (!item) return null;

            if (Date.now() > item.expiry) {
                this.cache.delete(key);
                return null;
            }
            return item.data;
        },

        clear() {
            this.cache.clear();
        },

        delete(key) {
            this.cache.delete(key);
        }
    };

    // Debounce function
    function debounce(func, wait, immediate = false) {
        let timeout;
        return function executedFunction(...args) {
            const context = this;
            const later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // Throttle function
    function throttle(func, limit) {
        let inThrottle;
        return function (...args) {
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Lazy load images
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });

            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for older browsers
            images.forEach(img => {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
        }
    }

    // Request Animation Frame throttle
    function rafThrottle(callback) {
        let requestId = null;
        let lastArgs;

        const later = () => {
            requestId = null;
            callback(...lastArgs);
        };

        return (...args) => {
            lastArgs = args;
            if (requestId === null) {
                requestId = requestAnimationFrame(later);
            }
        };
    }

    // Optimized AJAX request with caching
    function cachedAjax(options) {
        const cacheKey = options.url + JSON.stringify(options.data || {});
        const cached = ApiCache.get(cacheKey);

        if (cached && !options.forceRefresh) {
            if (options.success) {
                options.success(cached);
            }
            return Promise.resolve(cached);
        }

        return new Promise((resolve, reject) => {
            $.ajax({
                ...options,
                success: function (response) {
                    if (options.cacheTTL !== 0) {
                        ApiCache.set(cacheKey, response, options.cacheTTL);
                    }
                    if (options.success) {
                        options.success(response);
                    }
                    resolve(response);
                },
                error: function (xhr) {
                    if (options.error) {
                        options.error(xhr);
                    }
                    reject(xhr);
                }
            });
        });
    }

    // Batch DOM updates
    const DOMBatcher = {
        queue: [],
        scheduled: false,

        add(fn) {
            this.queue.push(fn);
            if (!this.scheduled) {
                this.scheduled = true;
                requestAnimationFrame(() => this.flush());
            }
        },

        flush() {
            const batch = this.queue.slice();
            this.queue = [];
            this.scheduled = false;
            batch.forEach(fn => fn());
        }
    };

    // Preload critical resources
    function preloadResources(urls) {
        urls.forEach(url => {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = url;
            document.head.appendChild(link);
        });
    }

    // Detect slow connection
    function isSlowConnection() {
        const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        if (connection) {
            return connection.effectiveType === 'slow-2g' ||
                connection.effectiveType === '2g' ||
                connection.saveData === true;
        }
        return false;
    }

    // Reduce motion for users who prefer it
    function prefersReducedMotion() {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    // Optimized scroll handler
    const optimizedScroll = rafThrottle((callback) => {
        callback();
    });

    // Memory-efficient event delegation
    function delegate(parent, eventType, selector, handler) {
        parent.addEventListener(eventType, function (event) {
            let target = event.target;
            while (target && target !== parent) {
                if (target.matches(selector)) {
                    handler.call(target, event);
                    break;
                }
                target = target.parentNode;
            }
        });
    }

    // Initialize performance optimizations
    function init() {
        // Lazy load images on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', lazyLoadImages);
        } else {
            lazyLoadImages();
        }

        // Reduce animations on slow connections or reduced motion preference
        if (isSlowConnection() || prefersReducedMotion()) {
            document.documentElement.classList.add('reduce-animations');
        }

        // Preload common API endpoints after page load
        setTimeout(() => {
            preloadResources([
                '/api/dashboard/stats',
                '/api/notifications'
            ]);
        }, 2000);
    }

    // Export utilities
    window.HakaiPerformance = {
        ApiCache,
        debounce,
        throttle,
        lazyLoadImages,
        rafThrottle,
        cachedAjax,
        DOMBatcher,
        preloadResources,
        isSlowConnection,
        prefersReducedMotion,
        optimizedScroll,
        delegate,
        init
    };

    // Auto-initialize
    init();

})(window);
