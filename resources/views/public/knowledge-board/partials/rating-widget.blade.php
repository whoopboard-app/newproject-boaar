@if($ratingSettings && $ratingSettings->apply_to_knowledge_board)
<div class="rating-widget" id="ratingWidget" data-article-id="{{ $article->id }}" data-board-id="{{ $knowledgeBoard->id }}">
    <div class="rating-question">{{ $ratingSettings->question_text }}</div>

    <div class="rating-options" id="ratingOptions">
        @switch($ratingSettings->rating_type)
            @case('yes_no')
                <div class="rating-yes-no">
                    <button type="button" class="rating-btn rating-btn-yes" data-rating="yes" onclick="submitRating('yes')">
                        <i class="ti ti-thumb-up"></i>
                        <span>Yes</span>
                    </button>
                    <button type="button" class="rating-btn rating-btn-no" data-rating="no" onclick="submitRating('no')">
                        <i class="ti ti-thumb-down"></i>
                        <span>No</span>
                    </button>
                </div>
                @break

            @case('emoji')
                <div class="rating-emoji">
                    <button type="button" class="rating-emoji-btn" data-rating="1" onclick="submitRating(1)" title="Very Unhappy">&#128545;</button>
                    <button type="button" class="rating-emoji-btn" data-rating="2" onclick="submitRating(2)" title="Unhappy">&#128577;</button>
                    <button type="button" class="rating-emoji-btn" data-rating="3" onclick="submitRating(3)" title="Neutral">&#128528;</button>
                    <button type="button" class="rating-emoji-btn" data-rating="4" onclick="submitRating(4)" title="Happy">&#128578;</button>
                    <button type="button" class="rating-emoji-btn" data-rating="5" onclick="submitRating(5)" title="Very Happy">&#128512;</button>
                </div>
                @break

            @case('star')
                <div class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="rating-star-btn" data-rating="{{ $i }}" onclick="submitRating({{ $i }})" onmouseenter="highlightStars({{ $i }})" onmouseleave="resetStars()">
                            <i class="ti ti-star"></i>
                        </button>
                    @endfor
                </div>
                @break

            @case('numeric')
                <div class="rating-numeric">
                    @for($i = 1; $i <= 10; $i++)
                        <button type="button" class="rating-numeric-btn" data-rating="{{ $i }}" onclick="submitRating({{ $i }})">{{ $i }}</button>
                    @endfor
                </div>
                @break

            @case('comment_only')
                <div class="rating-comment-only">
                    <textarea class="rating-comment-input" id="ratingComment" placeholder="Share your feedback..." rows="3"></textarea>
                    <button type="button" class="rating-submit-btn" onclick="submitComment()">Submit Feedback</button>
                </div>
                @break
        @endswitch
    </div>

    <div class="rating-feedback" id="ratingFeedback" style="display: none;">
        <div class="rating-feedback-success">
            <i class="ti ti-circle-check"></i>
            <span>Thank you for your feedback!</span>
        </div>
    </div>

    @if($ratingSettings->rating_type !== 'comment_only')
    <div class="rating-comment-section" id="ratingCommentSection" style="display: none;">
        <textarea class="rating-comment-input" id="ratingCommentAfter" placeholder="Any additional comments? (optional)" rows="2"></textarea>
        <button type="button" class="rating-submit-comment-btn" onclick="submitAdditionalComment()">Submit Comment</button>
    </div>
    @endif
</div>

<style>
.rating-widget {
    margin-top: 32px;
    padding: 24px;
    background: #f8f9fa;
    border-radius: 12px;
    text-align: center;
    border: 1px solid #e9ecef;
}

.rating-question {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 16px;
}

.rating-options {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Yes/No Style */
.rating-yes-no {
    display: flex;
    gap: 12px;
}

.rating-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    border: 2px solid;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}

.rating-btn-yes {
    border-color: #28a745;
    color: #28a745;
}

.rating-btn-yes:hover, .rating-btn-yes.selected {
    background: #28a745;
    color: white;
}

.rating-btn-no {
    border-color: #dc3545;
    color: #dc3545;
}

.rating-btn-no:hover, .rating-btn-no.selected {
    background: #dc3545;
    color: white;
}

/* Emoji Style */
.rating-emoji {
    display: flex;
    gap: 8px;
}

.rating-emoji-btn {
    font-size: 32px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s;
    opacity: 0.6;
}

.rating-emoji-btn:hover, .rating-emoji-btn.selected {
    opacity: 1;
    transform: scale(1.2);
    background: rgba(0,0,0,0.05);
}

/* Star Style */
.rating-stars {
    display: flex;
    gap: 4px;
}

.rating-star-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    font-size: 28px;
    color: #ddd;
    transition: all 0.2s;
}

.rating-star-btn:hover, .rating-star-btn.highlighted, .rating-star-btn.selected {
    color: #ffc107;
}

.rating-star-btn.selected i {
    font-weight: bold;
}

/* Numeric Style */
.rating-numeric {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: center;
}

.rating-numeric-btn {
    width: 36px;
    height: 36px;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    background: white;
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    cursor: pointer;
    transition: all 0.2s;
}

.rating-numeric-btn:hover, .rating-numeric-btn.selected {
    border-color: #007bff;
    background: #007bff;
    color: white;
}

/* Comment Only Style */
.rating-comment-only {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
}

.rating-comment-input {
    width: 100%;
    padding: 12px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    font-size: 14px;
    resize: vertical;
    margin-bottom: 12px;
}

.rating-comment-input:focus {
    outline: none;
    border-color: #007bff;
}

.rating-submit-btn, .rating-submit-comment-btn {
    padding: 10px 24px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.rating-submit-btn:hover, .rating-submit-comment-btn:hover {
    background: #0056b3;
}

/* Feedback Success */
.rating-feedback {
    padding: 16px;
}

.rating-feedback-success {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: #28a745;
    font-weight: 600;
}

.rating-feedback-success i {
    font-size: 24px;
}

/* Comment Section After Rating */
.rating-comment-section {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #dee2e6;
}

/* Disabled state */
.rating-widget.submitted .rating-options {
    pointer-events: none;
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .rating-widget {
        padding: 16px;
    }

    .rating-numeric {
        gap: 4px;
    }

    .rating-numeric-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }

    .rating-emoji-btn {
        font-size: 28px;
    }
}
</style>

<script>
let selectedRating = null;
let ratingSubmitted = false;

function submitRating(rating) {
    if (ratingSubmitted) return;

    selectedRating = rating;
    ratingSubmitted = true;

    // Visual feedback
    const widget = document.getElementById('ratingWidget');
    const options = document.getElementById('ratingOptions');
    const feedback = document.getElementById('ratingFeedback');
    const commentSection = document.getElementById('ratingCommentSection');

    // Mark as submitted
    widget.classList.add('submitted');

    // Highlight selected option
    highlightSelected(rating);

    // Show success message
    options.style.display = 'none';
    feedback.style.display = 'block';

    // Show comment section for additional feedback (if not comment_only)
    @if($ratingSettings->rating_type !== 'comment_only')
    setTimeout(() => {
        if (commentSection) {
            commentSection.style.display = 'block';
        }
    }, 1000);
    @endif

    // Send rating to server
    saveRating(rating);
}

function highlightSelected(rating) {
    const ratingType = '{{ $ratingSettings->rating_type }}';

    if (ratingType === 'yes_no') {
        const btn = document.querySelector(`.rating-btn[data-rating="${rating}"]`);
        if (btn) btn.classList.add('selected');
    } else if (ratingType === 'emoji') {
        const btn = document.querySelector(`.rating-emoji-btn[data-rating="${rating}"]`);
        if (btn) btn.classList.add('selected');
    } else if (ratingType === 'star') {
        const buttons = document.querySelectorAll('.rating-star-btn');
        buttons.forEach((btn, index) => {
            if (index < rating) {
                btn.classList.add('selected');
                btn.innerHTML = '<i class="ti ti-star-filled"></i>';
            }
        });
    } else if (ratingType === 'numeric') {
        const btn = document.querySelector(`.rating-numeric-btn[data-rating="${rating}"]`);
        if (btn) btn.classList.add('selected');
    }
}

function highlightStars(count) {
    if (ratingSubmitted) return;
    const buttons = document.querySelectorAll('.rating-star-btn');
    buttons.forEach((btn, index) => {
        if (index < count) {
            btn.classList.add('highlighted');
            btn.innerHTML = '<i class="ti ti-star-filled"></i>';
        } else {
            btn.classList.remove('highlighted');
            btn.innerHTML = '<i class="ti ti-star"></i>';
        }
    });
}

function resetStars() {
    if (ratingSubmitted) return;
    const buttons = document.querySelectorAll('.rating-star-btn');
    buttons.forEach(btn => {
        btn.classList.remove('highlighted');
        btn.innerHTML = '<i class="ti ti-star"></i>';
    });
}

function submitComment() {
    const comment = document.getElementById('ratingComment').value.trim();
    if (!comment) return;

    const articleId = document.getElementById('ratingWidget').dataset.articleId;
    const ratingType = '{{ $ratingSettings->rating_type }}';

    // Show success
    const widget = document.getElementById('ratingWidget');
    const options = document.getElementById('ratingOptions');
    const feedback = document.getElementById('ratingFeedback');

    options.style.display = 'none';
    feedback.style.display = 'block';
    ratingSubmitted = true;

    // Store in localStorage
    const ratingKey = `article_rating_${articleId}`;
    localStorage.setItem(ratingKey, 'comment');

    // Submit to server
    fetch('{{ route("public.rating.submit") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            rateable_type: 'article',
            rateable_id: articleId,
            rating_value: 'comment',
            rating_type: ratingType,
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.log('Rating response:', data.message);
        }
    })
    .catch(error => console.error('Rating error:', error));
}

function submitAdditionalComment() {
    const comment = document.getElementById('ratingCommentAfter').value.trim();
    if (!comment) return;

    const articleId = document.getElementById('ratingWidget').dataset.articleId;

    const btn = document.querySelector('.rating-submit-comment-btn');
    btn.textContent = 'Submitted!';
    btn.disabled = true;
    btn.style.background = '#28a745';

    // Submit additional comment to server
    fetch('{{ route("public.rating.comment") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            rateable_type: 'article',
            rateable_id: articleId,
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.log('Comment response:', data.message);
        }
    })
    .catch(error => console.error('Comment error:', error));
}

// Save rating to server
function saveRating(rating) {
    const articleId = document.getElementById('ratingWidget').dataset.articleId;
    const boardId = document.getElementById('ratingWidget').dataset.boardId;
    const ratingType = '{{ $ratingSettings->rating_type }}';

    // Store in localStorage to prevent duplicate ratings
    const ratingKey = `article_rating_${articleId}`;
    localStorage.setItem(ratingKey, rating);

    // Submit to server
    fetch('{{ route("public.rating.submit") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            rateable_type: 'article',
            rateable_id: articleId,
            rating_value: String(rating),
            rating_type: ratingType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.log('Rating response:', data.message);
        }
    })
    .catch(error => console.error('Rating error:', error));
}

// Check if already rated on page load
document.addEventListener('DOMContentLoaded', function() {
    const articleId = document.getElementById('ratingWidget')?.dataset.articleId;
    if (articleId) {
        const ratingKey = `article_rating_${articleId}`;
        const existingRating = localStorage.getItem(ratingKey);
        if (existingRating) {
            ratingSubmitted = true;
            highlightSelected(existingRating);
            document.getElementById('ratingWidget')?.classList.add('submitted');
            const feedback = document.getElementById('ratingFeedback');
            const options = document.getElementById('ratingOptions');
            if (feedback && options) {
                options.style.display = 'none';
                feedback.style.display = 'block';
                feedback.querySelector('.rating-feedback-success span').textContent = 'You already rated this article. Thank you!';
            }
        }
    }
});
</script>
@endif
