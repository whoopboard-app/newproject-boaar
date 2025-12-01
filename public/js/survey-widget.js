/**
 * WhoopBoard Survey Widget
 * Embeddable survey widget for collecting NPS, CSAT, and feedback
 */
(function() {
    'use strict';

    // Prevent multiple initializations
    if (window.WhoopSurvey) {
        return;
    }

    const STORAGE_PREFIX = 'whoopsurvey_';
    const SESSION_KEY = STORAGE_PREFIX + 'session';

    // Widget state
    let config = null;
    let container = null;
    let isVisible = false;
    let currentStep = 'rating'; // 'rating' | 'feedback' | 'thank_you'
    let selectedScore = null;
    let identifiedUser = null;

    /**
     * Main initialization
     */
    function init(surveyId, baseUrl, options = {}) {
        if (!surveyId) {
            console.error('WhoopSurvey: Survey ID is required');
            return;
        }

        // Store base URL (remove trailing slash)
        const apiBase = (baseUrl || '').replace(/\/$/, '');

        // Fetch survey configuration
        fetchConfig(surveyId, apiBase)
            .then(cfg => {
                config = cfg;
                config._apiBase = apiBase;
                config._surveyId = surveyId;

                // Apply user options
                if (options.user) {
                    identifiedUser = options.user;
                }

                // Check if we should show the survey
                if (shouldShowSurvey()) {
                    setupTrigger();
                }
            })
            .catch(err => {
                console.error('WhoopSurvey: Failed to load survey config', err);
            });
    }

    /**
     * Fetch survey configuration from API
     */
    async function fetchConfig(surveyId, apiBase) {
        const response = await fetch(`${apiBase}/api/survey/${surveyId}/config`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return response.json();
    }

    /**
     * Check if survey should be displayed based on settings
     */
    function shouldShowSurvey() {
        // Check if user identification is required
        if (config.only_identified_users && !identifiedUser) {
            return false;
        }

        // Check session-based skip
        if (config.skip_if_answered_in_session) {
            const sessionData = getSessionData();
            if (sessionData.answeredThisSession) {
                return false;
            }
        }

        // Check localStorage for response/close frequency
        const storageKey = STORAGE_PREFIX + config._surveyId;
        const storedData = getStoredData(storageKey);

        if (storedData) {
            // Check if user responded
            if (storedData.responded) {
                if (!canShowAfterAction(storedData.respondedAt, config.on_response_action,
                    config.on_response_show_after_value, config.on_response_show_after_unit)) {
                    return false;
                }
            }

            // Check if user closed without responding
            if (storedData.closed && !storedData.responded) {
                if (!canShowAfterAction(storedData.closedAt, config.on_close_action,
                    config.on_close_show_after_value, config.on_close_show_after_unit)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check if enough time has passed to show survey again
     */
    function canShowAfterAction(timestamp, action, value, unit) {
        if (action === 'never_show') {
            return false;
        }

        if (action === 'show_after' && timestamp && value && unit) {
            const actionTime = new Date(timestamp);
            const now = new Date();
            let diff;

            switch (unit) {
                case 'hours':
                    diff = (now - actionTime) / (1000 * 60 * 60);
                    break;
                case 'days':
                    diff = (now - actionTime) / (1000 * 60 * 60 * 24);
                    break;
                case 'months':
                    diff = (now - actionTime) / (1000 * 60 * 60 * 24 * 30);
                    break;
                default:
                    diff = value + 1; // Show by default if unit unknown
            }

            return diff >= value;
        }

        return true;
    }

    /**
     * Set up the trigger for showing the survey
     */
    function setupTrigger() {
        const triggerType = config.trigger_type || 'page_load';
        const delay = (config.trigger_delay_seconds || 0) * 1000;

        switch (triggerType) {
            case 'page_load':
                setTimeout(() => show(), delay);
                break;

            case 'page_leave':
                document.addEventListener('mouseleave', function handler(e) {
                    if (e.clientY < 10) {
                        document.removeEventListener('mouseleave', handler);
                        setTimeout(() => show(), Math.min(delay, 500));
                    }
                });
                break;

            case 'scroll':
                let scrollTriggered = false;
                window.addEventListener('scroll', function handler() {
                    if (scrollTriggered) return;

                    const scrollPercent = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
                    if (scrollPercent >= 50) {
                        scrollTriggered = true;
                        window.removeEventListener('scroll', handler);
                        setTimeout(() => show(), delay);
                    }
                });
                break;

            case 'code_trigger':
                // Manual trigger via WhoopSurvey.show()
                break;

            default:
                setTimeout(() => show(), delay);
        }
    }

    /**
     * Show the survey widget
     */
    function show() {
        if (isVisible || !config) return;

        createWidget();
        isVisible = true;

        // Animate in
        requestAnimationFrame(() => {
            if (container) {
                container.classList.add('whoopsurvey-visible');
            }
        });
    }

    /**
     * Hide the survey widget
     */
    function hide(wasResponded = false) {
        if (!isVisible || !container) return;

        container.classList.remove('whoopsurvey-visible');

        setTimeout(() => {
            if (container && container.parentNode) {
                container.parentNode.removeChild(container);
            }
            container = null;
            isVisible = false;
        }, 300);

        // Store close data
        const storageKey = STORAGE_PREFIX + config._surveyId;
        const storedData = getStoredData(storageKey) || {};

        if (!wasResponded) {
            storedData.closed = true;
            storedData.closedAt = new Date().toISOString();
        }

        setStoredData(storageKey, storedData);

        // Mark session
        if (config.skip_if_answered_in_session) {
            const sessionData = getSessionData();
            sessionData.answeredThisSession = true;
            setSessionData(sessionData);
        }
    }

    /**
     * Create the widget DOM
     */
    function createWidget() {
        // Inject styles
        injectStyles();

        // Create container
        container = document.createElement('div');
        container.className = 'whoopsurvey-container';
        container.setAttribute('data-position', config.position || 'bottom_right');
        container.setAttribute('data-theme', config.theme || 'light');

        // Apply accent color
        if (config.accent_color) {
            container.style.setProperty('--whoopsurvey-accent', config.accent_color);
        }

        // Render initial content
        renderStep();

        document.body.appendChild(container);
    }

    /**
     * Render current step
     */
    function renderStep() {
        if (!container) return;

        let html = '';

        switch (currentStep) {
            case 'rating':
                html = renderRatingStep();
                break;
            case 'feedback':
                html = renderFeedbackStep();
                break;
            case 'thank_you':
                html = renderThankYouStep();
                break;
        }

        container.innerHTML = html;
        attachEventListeners();
    }

    /**
     * Render the rating step based on survey type
     */
    function renderRatingStep() {
        let ratingContent = '';

        switch (config.type) {
            case 'nps':
                ratingContent = renderNPSRating();
                break;
            case 'csat':
                ratingContent = renderCSATRating();
                break;
            case 'quick_poll':
                ratingContent = renderQuickPoll();
                break;
            case 'open_feedback':
            case 'idea_pool':
                // Skip to feedback step for text-only surveys
                currentStep = 'feedback';
                return renderFeedbackStep();
            default:
                ratingContent = renderNPSRating();
        }

        return `
            <div class="whoopsurvey-widget">
                <button class="whoopsurvey-close" aria-label="Close survey">&times;</button>
                <div class="whoopsurvey-question">${escapeHtml(config.question || 'How was your experience?')}</div>
                <div class="whoopsurvey-rating">
                    ${ratingContent}
                </div>
                ${config.show_labels !== false ? renderLabels() : ''}
                ${!config.hide_branding ? renderBranding() : ''}
            </div>
        `;
    }

    /**
     * Render NPS (0-10) rating scale
     */
    function renderNPSRating() {
        let buttons = '';
        for (let i = 0; i <= 10; i++) {
            buttons += `<button class="whoopsurvey-score-btn" data-score="${i}">${i}</button>`;
        }
        return `<div class="whoopsurvey-nps-scale">${buttons}</div>`;
    }

    /**
     * Render CSAT (1-5) rating scale with emoji faces
     */
    function renderCSATRating() {
        const faces = [
            { score: 1, emoji: '😠', label: 'Very Dissatisfied' },
            { score: 2, emoji: '😞', label: 'Dissatisfied' },
            { score: 3, emoji: '😐', label: 'Neutral' },
            { score: 4, emoji: '😊', label: 'Satisfied' },
            { score: 5, emoji: '😍', label: 'Very Satisfied' },
        ];

        return `
            <div class="whoopsurvey-csat-scale">
                ${faces.map(f => `
                    <button class="whoopsurvey-face-btn" data-score="${f.score}" title="${f.label}">
                        <span class="whoopsurvey-emoji">${f.emoji}</span>
                    </button>
                `).join('')}
            </div>
        `;
    }

    /**
     * Render quick poll options
     */
    function renderQuickPoll() {
        const options = config.options || ['Option 1', 'Option 2', 'Option 3'];

        return `
            <div class="whoopsurvey-poll-options">
                ${options.map((opt, idx) => `
                    <button class="whoopsurvey-poll-btn" data-score="${idx + 1}" data-option="${escapeHtml(opt)}">
                        ${escapeHtml(opt)}
                    </button>
                `).join('')}
            </div>
        `;
    }

    /**
     * Render scale labels
     */
    function renderLabels() {
        const minLabel = config.label_least_likely || 'Not at all likely';
        const maxLabel = config.label_most_likely || 'Extremely likely';

        return `
            <div class="whoopsurvey-labels">
                <span class="whoopsurvey-label-min">${escapeHtml(minLabel)}</span>
                <span class="whoopsurvey-label-max">${escapeHtml(maxLabel)}</span>
            </div>
        `;
    }

    /**
     * Render the feedback step
     */
    function renderFeedbackStep() {
        const question = config.follow_up_question || config.feedback_question || 'What is the main reason for your score?';
        const placeholder = config.feedback_placeholder || 'Share your thoughts...';
        const showBack = currentStep === 'feedback' && selectedScore !== null &&
            config.type !== 'open_feedback' && config.type !== 'idea_pool';

        return `
            <div class="whoopsurvey-widget">
                <button class="whoopsurvey-close" aria-label="Close survey">&times;</button>
                <div class="whoopsurvey-question">${escapeHtml(question)}</div>
                <div class="whoopsurvey-feedback">
                    <textarea class="whoopsurvey-textarea" placeholder="${escapeHtml(placeholder)}" rows="4"></textarea>
                </div>
                <div class="whoopsurvey-actions">
                    ${showBack ? `<button class="whoopsurvey-btn whoopsurvey-btn-back">${escapeHtml(config.label_back_button || 'Back')}</button>` : ''}
                    ${config.feedback_form_option !== 'required' ?
                        `<button class="whoopsurvey-btn whoopsurvey-btn-skip">${escapeHtml(config.label_skip_button || 'Skip')}</button>` : ''}
                    <button class="whoopsurvey-btn whoopsurvey-btn-submit">${escapeHtml(config.label_submit_button || 'Submit')}</button>
                </div>
                ${!config.hide_branding ? renderBranding() : ''}
            </div>
        `;
    }

    /**
     * Render the thank you step
     */
    function renderThankYouStep() {
        const message = config.thank_you_message || 'Thank you for your feedback!';

        return `
            <div class="whoopsurvey-widget whoopsurvey-thank-you">
                <button class="whoopsurvey-close" aria-label="Close survey">&times;</button>
                <div class="whoopsurvey-checkmark">
                    <svg viewBox="0 0 24 24" width="48" height="48">
                        <circle cx="12" cy="12" r="11" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path d="M7 12l3 3 7-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="whoopsurvey-message">${escapeHtml(message)}</div>
                ${!config.hide_branding ? renderBranding() : ''}
            </div>
        `;
    }

    /**
     * Render branding
     */
    function renderBranding() {
        return `
            <div class="whoopsurvey-branding">
                Powered by <a href="https://whoopboard.com" target="_blank" rel="noopener">WhoopBoard</a>
            </div>
        `;
    }

    /**
     * Attach event listeners
     */
    function attachEventListeners() {
        if (!container) return;

        // Close button
        const closeBtn = container.querySelector('.whoopsurvey-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => hide(false));
        }

        // Score buttons (NPS, CSAT, Poll)
        const scoreBtns = container.querySelectorAll('.whoopsurvey-score-btn, .whoopsurvey-face-btn, .whoopsurvey-poll-btn');
        scoreBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                selectedScore = parseInt(e.currentTarget.dataset.score, 10);

                // Highlight selected
                scoreBtns.forEach(b => b.classList.remove('selected'));
                e.currentTarget.classList.add('selected');

                // Move to feedback step after brief delay
                setTimeout(() => {
                    if (config.feedback_form_option === 'dont_show') {
                        // Submit immediately without feedback
                        submitResponse(selectedScore, null);
                    } else {
                        currentStep = 'feedback';
                        renderStep();
                    }
                }, 300);
            });
        });

        // Back button
        const backBtn = container.querySelector('.whoopsurvey-btn-back');
        if (backBtn) {
            backBtn.addEventListener('click', () => {
                currentStep = 'rating';
                renderStep();
            });
        }

        // Skip button
        const skipBtn = container.querySelector('.whoopsurvey-btn-skip');
        if (skipBtn) {
            skipBtn.addEventListener('click', () => {
                submitResponse(selectedScore, null);
            });
        }

        // Submit button
        const submitBtn = container.querySelector('.whoopsurvey-btn-submit');
        if (submitBtn) {
            submitBtn.addEventListener('click', () => {
                const textarea = container.querySelector('.whoopsurvey-textarea');
                const feedback = textarea ? textarea.value.trim() : null;

                // Check if feedback is required
                if (config.feedback_form_option === 'required' && !feedback) {
                    textarea.classList.add('whoopsurvey-error');
                    textarea.focus();
                    return;
                }

                submitResponse(selectedScore, feedback);
            });
        }

        // Remove error class on input
        const textarea = container.querySelector('.whoopsurvey-textarea');
        if (textarea) {
            textarea.addEventListener('input', () => {
                textarea.classList.remove('whoopsurvey-error');
            });
        }
    }

    /**
     * Submit response to API
     */
    async function submitResponse(score, feedback) {
        const payload = {
            score: score,
            feedback: feedback,
            metadata: {
                url: window.location.href,
                referrer: document.referrer,
                user_agent: navigator.userAgent,
            },
        };

        // Add user info if identified
        if (identifiedUser) {
            if (identifiedUser.id) payload.respondent_id = identifiedUser.id;
            if (identifiedUser.email) payload.respondent_email = identifiedUser.email;
        }

        try {
            const response = await fetch(`${config._apiBase}/api/survey/${config._surveyId}/respond`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            // Store response data
            const storageKey = STORAGE_PREFIX + config._surveyId;
            const storedData = getStoredData(storageKey) || {};
            storedData.responded = true;
            storedData.respondedAt = new Date().toISOString();
            setStoredData(storageKey, storedData);

            // Show thank you
            currentStep = 'thank_you';
            renderStep();

            // Auto-close after delay
            setTimeout(() => hide(true), 3000);

        } catch (err) {
            console.error('WhoopSurvey: Failed to submit response', err);
            // Still show thank you on error to not frustrate user
            currentStep = 'thank_you';
            renderStep();
            setTimeout(() => hide(true), 3000);
        }
    }

    /**
     * Inject widget styles
     */
    function injectStyles() {
        if (document.getElementById('whoopsurvey-styles')) return;

        const styles = document.createElement('style');
        styles.id = 'whoopsurvey-styles';
        styles.textContent = `
            .whoopsurvey-container {
                --whoopsurvey-accent: #6366f1;
                --whoopsurvey-bg: #ffffff;
                --whoopsurvey-text: #1f2937;
                --whoopsurvey-text-secondary: #6b7280;
                --whoopsurvey-border: #e5e7eb;
                --whoopsurvey-shadow: 0 10px 40px rgba(0,0,0,0.15);

                position: fixed;
                z-index: 999999;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                font-size: 14px;
                line-height: 1.5;
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.3s ease, transform 0.3s ease;
            }

            .whoopsurvey-container.whoopsurvey-visible {
                opacity: 1;
                transform: translateY(0);
            }

            /* Positioning */
            .whoopsurvey-container[data-position="bottom_right"] {
                bottom: 20px;
                right: 20px;
            }

            .whoopsurvey-container[data-position="bottom_left"] {
                bottom: 20px;
                left: 20px;
            }

            .whoopsurvey-container[data-position="bottom_center"] {
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%) translateY(20px);
            }

            .whoopsurvey-container[data-position="bottom_center"].whoopsurvey-visible {
                transform: translateX(-50%) translateY(0);
            }

            .whoopsurvey-container[data-position="center"] {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(0.95);
            }

            .whoopsurvey-container[data-position="center"].whoopsurvey-visible {
                transform: translate(-50%, -50%) scale(1);
            }

            /* Themes */
            .whoopsurvey-container[data-theme="dark"] {
                --whoopsurvey-bg: #1f2937;
                --whoopsurvey-text: #f9fafb;
                --whoopsurvey-text-secondary: #9ca3af;
                --whoopsurvey-border: #374151;
            }

            .whoopsurvey-container[data-theme="purple"] {
                --whoopsurvey-bg: #4c1d95;
                --whoopsurvey-text: #f9fafb;
                --whoopsurvey-text-secondary: #c4b5fd;
                --whoopsurvey-border: #6d28d9;
                --whoopsurvey-accent: #a78bfa;
            }

            .whoopsurvey-container[data-theme="navy"] {
                --whoopsurvey-bg: #1e3a5f;
                --whoopsurvey-text: #f9fafb;
                --whoopsurvey-text-secondary: #93c5fd;
                --whoopsurvey-border: #2563eb;
                --whoopsurvey-accent: #60a5fa;
            }

            .whoopsurvey-widget {
                background: var(--whoopsurvey-bg);
                border: 1px solid var(--whoopsurvey-border);
                border-radius: 12px;
                box-shadow: var(--whoopsurvey-shadow);
                padding: 24px;
                min-width: 320px;
                max-width: 400px;
                position: relative;
            }

            .whoopsurvey-close {
                position: absolute;
                top: 12px;
                right: 12px;
                width: 28px;
                height: 28px;
                border: none;
                background: transparent;
                color: var(--whoopsurvey-text-secondary);
                font-size: 20px;
                cursor: pointer;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background 0.2s, color 0.2s;
            }

            .whoopsurvey-close:hover {
                background: var(--whoopsurvey-border);
                color: var(--whoopsurvey-text);
            }

            .whoopsurvey-question {
                color: var(--whoopsurvey-text);
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 20px;
                padding-right: 24px;
            }

            /* NPS Scale */
            .whoopsurvey-nps-scale {
                display: flex;
                gap: 4px;
                justify-content: center;
            }

            .whoopsurvey-score-btn {
                width: 32px;
                height: 36px;
                border: 1px solid var(--whoopsurvey-border);
                background: var(--whoopsurvey-bg);
                color: var(--whoopsurvey-text);
                font-size: 13px;
                font-weight: 500;
                cursor: pointer;
                border-radius: 6px;
                transition: all 0.2s;
            }

            .whoopsurvey-score-btn:hover {
                border-color: var(--whoopsurvey-accent);
                background: var(--whoopsurvey-accent);
                color: white;
            }

            .whoopsurvey-score-btn.selected {
                border-color: var(--whoopsurvey-accent);
                background: var(--whoopsurvey-accent);
                color: white;
            }

            /* CSAT Scale */
            .whoopsurvey-csat-scale {
                display: flex;
                gap: 8px;
                justify-content: center;
            }

            .whoopsurvey-face-btn {
                width: 48px;
                height: 48px;
                border: 2px solid var(--whoopsurvey-border);
                background: var(--whoopsurvey-bg);
                cursor: pointer;
                border-radius: 50%;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .whoopsurvey-face-btn:hover {
                border-color: var(--whoopsurvey-accent);
                transform: scale(1.1);
            }

            .whoopsurvey-face-btn.selected {
                border-color: var(--whoopsurvey-accent);
                background: var(--whoopsurvey-accent);
                transform: scale(1.1);
            }

            .whoopsurvey-emoji {
                font-size: 24px;
            }

            /* Poll Options */
            .whoopsurvey-poll-options {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .whoopsurvey-poll-btn {
                padding: 12px 16px;
                border: 1px solid var(--whoopsurvey-border);
                background: var(--whoopsurvey-bg);
                color: var(--whoopsurvey-text);
                font-size: 14px;
                text-align: left;
                cursor: pointer;
                border-radius: 8px;
                transition: all 0.2s;
            }

            .whoopsurvey-poll-btn:hover {
                border-color: var(--whoopsurvey-accent);
                background: color-mix(in srgb, var(--whoopsurvey-accent) 10%, var(--whoopsurvey-bg));
            }

            .whoopsurvey-poll-btn.selected {
                border-color: var(--whoopsurvey-accent);
                background: var(--whoopsurvey-accent);
                color: white;
            }

            /* Labels */
            .whoopsurvey-labels {
                display: flex;
                justify-content: space-between;
                margin-top: 8px;
                color: var(--whoopsurvey-text-secondary);
                font-size: 11px;
            }

            /* Feedback */
            .whoopsurvey-feedback {
                margin-bottom: 16px;
            }

            .whoopsurvey-textarea {
                width: 100%;
                padding: 12px;
                border: 1px solid var(--whoopsurvey-border);
                border-radius: 8px;
                background: var(--whoopsurvey-bg);
                color: var(--whoopsurvey-text);
                font-family: inherit;
                font-size: 14px;
                resize: vertical;
                min-height: 80px;
                box-sizing: border-box;
            }

            .whoopsurvey-textarea:focus {
                outline: none;
                border-color: var(--whoopsurvey-accent);
                box-shadow: 0 0 0 3px color-mix(in srgb, var(--whoopsurvey-accent) 20%, transparent);
            }

            .whoopsurvey-textarea.whoopsurvey-error {
                border-color: #ef4444;
            }

            .whoopsurvey-textarea::placeholder {
                color: var(--whoopsurvey-text-secondary);
            }

            /* Actions */
            .whoopsurvey-actions {
                display: flex;
                gap: 8px;
                justify-content: flex-end;
            }

            .whoopsurvey-btn {
                padding: 10px 16px;
                border: none;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s;
            }

            .whoopsurvey-btn-back,
            .whoopsurvey-btn-skip {
                background: transparent;
                color: var(--whoopsurvey-text-secondary);
            }

            .whoopsurvey-btn-back:hover,
            .whoopsurvey-btn-skip:hover {
                background: var(--whoopsurvey-border);
                color: var(--whoopsurvey-text);
            }

            .whoopsurvey-btn-submit {
                background: var(--whoopsurvey-accent);
                color: white;
            }

            .whoopsurvey-btn-submit:hover {
                filter: brightness(1.1);
            }

            /* Thank You */
            .whoopsurvey-thank-you {
                text-align: center;
                padding: 32px 24px;
            }

            .whoopsurvey-checkmark {
                color: var(--whoopsurvey-accent);
                margin-bottom: 16px;
            }

            .whoopsurvey-message {
                color: var(--whoopsurvey-text);
                font-size: 16px;
                font-weight: 500;
            }

            /* Branding */
            .whoopsurvey-branding {
                margin-top: 16px;
                text-align: center;
                font-size: 11px;
                color: var(--whoopsurvey-text-secondary);
            }

            .whoopsurvey-branding a {
                color: var(--whoopsurvey-accent);
                text-decoration: none;
            }

            .whoopsurvey-branding a:hover {
                text-decoration: underline;
            }

            /* Mobile responsiveness */
            @media (max-width: 480px) {
                .whoopsurvey-container[data-position="bottom_right"],
                .whoopsurvey-container[data-position="bottom_left"],
                .whoopsurvey-container[data-position="bottom_center"] {
                    left: 10px;
                    right: 10px;
                    bottom: 10px;
                    transform: translateY(20px);
                }

                .whoopsurvey-container[data-position="bottom_center"] {
                    transform: translateY(20px);
                }

                .whoopsurvey-container[data-position="bottom_right"].whoopsurvey-visible,
                .whoopsurvey-container[data-position="bottom_left"].whoopsurvey-visible,
                .whoopsurvey-container[data-position="bottom_center"].whoopsurvey-visible {
                    transform: translateY(0);
                }

                .whoopsurvey-widget {
                    min-width: unset;
                    max-width: unset;
                }

                .whoopsurvey-nps-scale {
                    gap: 2px;
                }

                .whoopsurvey-score-btn {
                    width: 28px;
                    height: 32px;
                    font-size: 12px;
                }
            }
        `;

        document.head.appendChild(styles);
    }

    /**
     * Helper: Escape HTML
     */
    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    /**
     * Helper: Get stored data from localStorage
     */
    function getStoredData(key) {
        try {
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : null;
        } catch (e) {
            return null;
        }
    }

    /**
     * Helper: Set stored data to localStorage
     */
    function setStoredData(key, data) {
        try {
            localStorage.setItem(key, JSON.stringify(data));
        } catch (e) {
            // localStorage not available
        }
    }

    /**
     * Helper: Get session data
     */
    function getSessionData() {
        try {
            const data = sessionStorage.getItem(SESSION_KEY);
            return data ? JSON.parse(data) : {};
        } catch (e) {
            return {};
        }
    }

    /**
     * Helper: Set session data
     */
    function setSessionData(data) {
        try {
            sessionStorage.setItem(SESSION_KEY, JSON.stringify(data));
        } catch (e) {
            // sessionStorage not available
        }
    }

    /**
     * Identify a user
     */
    function identify(user) {
        identifiedUser = user;
    }

    /**
     * Reset survey state (for testing)
     */
    function reset() {
        if (config) {
            const storageKey = STORAGE_PREFIX + config._surveyId;
            localStorage.removeItem(storageKey);
        }
        sessionStorage.removeItem(SESSION_KEY);

        if (container) {
            hide(false);
        }

        selectedScore = null;
        currentStep = 'rating';
    }

    // Expose public API
    window.WhoopSurvey = {
        init: init,
        show: show,
        hide: hide,
        identify: identify,
        reset: reset,
    };

})();
