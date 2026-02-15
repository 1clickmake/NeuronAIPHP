<?php include_header('Register', $siteConfig); ?>

<div class="container" style="max-width: 450px; margin-top: 50px;">
    <div class="glass-card">
        <h2 style="margin-bottom: 2rem; text-align: center;">Join Us</h2>
        
        <?php if (isset($error)): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fecaca; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="country" value="<?= $_SESSION['visitor_country'] ?? 'Unknown' ?>">
            <div class="form-group">
                <label>User ID</label>
                <input type="text" name="user_id" id="user_id" class="form-control" required>
                <div id="user_id_feedback" class="feedback-text"></div>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
                <div id="username_feedback" class="feedback-text"></div>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required>
                <div id="email_feedback" class="feedback-text"></div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top:1rem;">Create Account</button>
        </form>
        
        <p style="margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--text-muted);">
            Already have an account? <a href="/login" style="color: var(--primary); text-decoration: none;">Login here</a>
        </p>
    </div>
</div>

<style>
    .feedback-text {
        font-size: 0.85rem;
        margin-top: 0.3rem;
        min-height: 1.25rem;
        transition: color 0.3s;
    }
    .feedback-success {
        color: #10b981;
    }
    .feedback-error {
        color: #ef4444;
    }
    .input-error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
    }
    .input-success {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
    }
</style>

<script>
$(document).ready(function() {
    let timer;
    const delay = 500; // Delay in ms

    function checkDuplicate(field, value, feedbackId, inputId) {
        const feedback = $('#' + feedbackId);
        const input = $('#' + inputId);

        $.get('/auth/check-duplicate', { field: field, value: value }, function(response) {
            
            if (response.exists) {
                let msg = 'This ' + (field === 'user_id' ? 'ID' : 'email') + ' is already taken.';
                if (response.message) msg = response.message;
                
                feedback.text(msg).removeClass('feedback-success').addClass('feedback-error');
                input.removeClass('input-success').addClass('input-error');
            } else {
                feedback.text('Available!').removeClass('feedback-error').addClass('feedback-success');
                input.removeClass('input-error').addClass('input-success');
            }
        });
    }

    $('#user_id').on('input', function() {
        clearTimeout(timer);
        const value = $(this).val();
        const feedback = $('#user_id_feedback');
        const input = $(this);
        
        feedback.text('').removeClass('feedback-success feedback-error');
        input.removeClass('input-error input-success');

        if (value.length >= 3) {
            timer = setTimeout(function() {
                checkDuplicate('user_id', value, 'user_id_feedback', 'user_id');
            }, delay);
        }
    });

    $('#email').on('input', function() {
        clearTimeout(timer);
        const value = $(this).val();
        const feedback = $('#email_feedback');
        const input = $(this);

        feedback.text('').removeClass('feedback-success feedback-error');
        input.removeClass('input-error input-success');

        if (value.length >= 5 && value.includes('@')) {
            timer = setTimeout(function() {
                checkDuplicate('email', value, 'email_feedback', 'email');
            }, delay);
        }
    });

    $('#username').on('input', function() {
        clearTimeout(timer);
        const value = $(this).val();
        const feedback = $('#username_feedback');
        const input = $(this);

        feedback.text('').removeClass('feedback-success feedback-error');
        input.removeClass('input-error input-success');

        if (value.length >= 2) {
            timer = setTimeout(function() {
                checkDuplicate('username', value, 'username_feedback', 'username');
            }, delay);
        }
    });
});
</script>

<?php include_footer($siteConfig); ?>

<!-- Success Modal -->
<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
<div id="successModal" class="modal-overlay">
    <div class="modal-content glass-card success-box">
        <div class="success-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <h3>Welcome Aboard!</h3>
        <p>회원가입이 완료 되었습니다.<br>잠시 후 로그인 페이지로 이동합니다.</p>
        
        <div class="timer-bar">
            <div id="timerProgress" class="progress"></div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
            <a href="/login" class="btn btn-primary">지금 바로 로그인</a>
            <span style="font-size: 0.8rem; color: var(--text-muted); text-align: center;">
                <span id="countdown">3</span>초 후 자동 이동
            </span>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        animation: fadeIn 0.4s ease-out;
    }

    .success-box {
        max-width: 400px;
        width: 90%;
        text-align: center;
        transform: translateY(20px);
        animation: slideUp 0.5s ease-out forwards;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 3rem 2rem !important;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 0 30px var(--primary-glow);
    }

    .success-icon svg {
        width: 40px;
        height: 40px;
    }

    .success-box h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .success-box p {
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .timer-bar {
        width: 100%;
        height: 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .timer-bar .progress {
        height: 100%;
        background: var(--primary);
        width: 100%;
        animation: shrink 3s linear forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes shrink {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>

<script>
    let seconds = 3;
    const countdownEl = document.getElementById('countdown');
    
    const timer = setInterval(() => {
        seconds--;
        countdownEl.innerText = seconds;
        if (seconds <= 0) {
            clearInterval(timer);
            window.location.href = '/login';
        }
    }, 1000);

    // Overlay click to skip
    document.getElementById('successModal').addEventListener('click', function(e) {
        if (e.target === this) {
            window.location.href = '/login';
        }
    });
</script>
<?php endif; ?>
