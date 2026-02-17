<section class="contact-section">
    <div class="form-container">
        <div class="form-header">
            <h2>Contact Us</h2>
            <p>Do you have any questions? Please feel free to contact us directly.</p>
        </div>

        <form id="contact-form" onsubmit="submitContactForm(event)">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="target_info" value="contact">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" name="name" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="010-0000-0000">
                </div>
                <div class="form-group">
                    <label>Your Email</label>
                    <input type="email" name="email" required placeholder="john@example.com">
                </div>
            </div>
            
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" required placeholder="How can we help you?">
            </div>

            <div class="form-group" style="margin-bottom: 2.5rem;">
                <label>Message</label>
                <textarea name="message" rows="5" required placeholder="Enter your message here..."></textarea>
            </div>

            <div class="form-footer">
                <button type="submit" id="btn-submit-contact">
                    <i class="fa-solid fa-paper-plane"></i> Send Message
                </button>
            </div>
        </form>
    </div>
</section>

<script>
async function submitContactForm(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-submit-contact');
    const originalContent = btn.innerHTML;
    
    // Disable button & show loading
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
    
    const formData = new FormData(e.target);
    const data = {};
    formData.forEach((value, key) => data[key] = value);
    
    try {
        const response = await fetch('/contact/send', {
            method: 'POST',
            body: formData // Use FormData directly as it's standard
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Your message has been sent successfully!');
            e.target.reset();
        } else {
            alert('Error: ' + (result.message || 'Something went wrong.'));
        }
    } catch (error) {
        console.error('Contact Form Error:', error);
        alert('Failed to send message. Please try again later.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalContent;
    }
}
</script>
