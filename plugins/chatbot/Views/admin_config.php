<?php $title = 'Chatbot Configuration'; include_header($title); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-robot me-2 text-primary"></i> Chatbot Settings</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="/admin/chatbot/config" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <!-- Chatbot Behavior Settings -->
            <div class="glass-card mb-4">
                <h4 class="mb-3 text-primary"><i class="fa-solid fa-sliders me-2"></i> Behavior</h4>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="use_chatbot" value="1" id="useChatbot" <?= ($config['use_chatbot'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-bold" for="useChatbot">Enable Chatbot Widget</label>
                    <div class="form-text">Show the chatbot icon on the bottom right of the public site.</div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Guest Daily Limit</label>
                        <div class="input-group">
                            <input type="number" name="chatbot_limit_guest" class="form-control" value="<?= $config['chatbot_limit_guest'] ?? 5 ?>" min="0">
                            <span class="input-group-text">msgs</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Member Daily Limit</label>
                        <div class="input-group">
                            <input type="number" name="chatbot_limit_member" class="form-control" value="<?= $config['chatbot_limit_member'] ?? 20 ?>" min="0">
                            <span class="input-group-text">msgs</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI API Keys (Standalone Mode) -->
            <div class="glass-card mb-4">
                <h4 class="mb-3 text-primary"><i class="fa-solid fa-key me-2"></i> AI API Configuration</h4>
                <p class="text-muted small mb-3">
                    If AI Manager plugin is not installed, you must configure API keys here.<br>
                    <span class="text-danger">* At least one API key is required for the chatbot to work.</span>
                </p>

                <div class="mb-3">
                    <label class="form-label">Default Model</label>
                    <input type="text" name="default_model" class="form-control" value="<?= htmlspecialchars($config['default_model'] ?? 'gpt-4o') ?>" placeholder="e.g. gpt-4o, gemini-1.5-flash">
                </div>

                <div class="mb-3">
                    <label class="form-label">OpenAI API Key</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-microchip"></i></span>
                        <input type="password" name="openai_key" class="form-control" value="<?= htmlspecialchars($config['openai_key'] ?? '') ?>" placeholder="sk-...">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Google Gemini API Key</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-brands fa-google"></i></span>
                        <input type="password" name="gemini_key" class="form-control" value="<?= htmlspecialchars($config['gemini_key'] ?? '') ?>" placeholder="AIza...">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Anthropic Claude API Key</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-brain"></i></span>
                        <input type="password" name="claude_key" class="form-control" value="<?= htmlspecialchars($config['claude_key'] ?? '') ?>" placeholder="sk-ant-...">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Groq API Key</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-bolt"></i></span>
                        <input type="password" name="groq_key" class="form-control" value="<?= htmlspecialchars($config['groq_key'] ?? '') ?>" placeholder="gsk_...">
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-lg">
                    <i class="fa-solid fa-save me-2"></i> Save Configuration
                </button>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="glass-card bg-primary text-white mb-4">
            <h5 class="mb-3"><i class="fa-solid fa-circle-info me-2"></i> How it works</h5>
            <p class="small opacity-75">
                The chatbot uses the configured AI model to answer user questions based on your site's content (Posts, Pages, FAQ).
            </p>
            <hr class="border-white opacity-25">
            <p class="small opacity-75 mb-0">
                Ensure you have sufficient credits in your chosen AI provider's account.
            </p>
        </div>
    </div>
</div>

<?php include_footer(); ?>
