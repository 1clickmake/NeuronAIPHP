<?php $title = 'Mail Logs'; include_admin_header($title); ?>

<div class="admin-header-flex">
    <div>
        <h1 style="margin: 0;">Mail Logs</h1>
        <p class="text-muted-small" style="margin: 0;">History of sent emails.</p>
    </div>
    <a href="/admin/mail" class="btn btn-secondary">
        <i class="fa-solid fa-paper-plane"></i> Send Mail
    </a>
</div>

<div class="glass-card">
    <div class="table-responsive">
        <style>
            .log-subject {
                cursor: pointer;
                color: #6366f1;
                text-decoration: none;
                font-weight: 500;
            }
            .log-subject:hover {
                text-decoration: underline;
                color: #818cf8;
            }
        </style>
        <table class="table table-dark table-hover table-striped text-center">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 150px;">Target</th>
                    <th>Subject</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 160px;">Sent At</th>
                    <th>Error Message</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No logs found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= $log['id'] ?></td>
                            <td>
                                <!-- Display target_info if available, else fallback to recipient (truncated) -->
                                <?php 
                                    $target = $log['target_info'] ?? $log['recipient'];
                                    // if it's a long list of emails and no target_info, truncate
                                    if (empty($log['target_info']) && strlen($target) > 20) {
                                        $target = mb_substr($target, 0, 20) . '...';
                                    }
                                    echo htmlspecialchars($target);
                                ?>
                            </td>
                            <td class="text-start">
                                <span class="log-subject" onclick='openLogDetail(<?= htmlspecialchars(json_encode($log), ENT_QUOTES, "UTF-8") ?>)'>
                                    <?= htmlspecialchars($log['subject']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($log['status'] === 'success'): ?>
                                    <span class="badge badge-success">Success</span>
                                <?php elseif ($log['status'] === 'partial'): ?>
                                    <span class="badge badge-warning">Partial</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Failed</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $log['sent_at'] ?></td>
                            <td><?= htmlspecialchars($log['error_message'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?= get_pagination($page, $totalPages) ?>
</div>

<!-- Mail Detail Modal -->
<div id="mailDetailModal" class="mail-modal-overlay">
    <div class="glass-card mail-modal-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="fa-solid fa-envelope-open-text me-2"></i> Mail Details</h3>
            <button onclick="$('#mailDetailModal').fadeOut()" class="btn btn-sm btn-secondary"><i class="fa-solid fa-times"></i></button>
        </div>

        <div class="detail-group mb-4">
            <label class="text-muted-small d-block mb-1">Subject</label>
            <div id="detail-subject" class="fw-bold fs-5 p-2 bg-dark-soft rounded"></div>
        </div>

        <div class="detail-group mb-4">
            <label class="text-muted-small d-block mb-1">Recipients</label>
            <div id="detail-recipients" class="mail-recipients-box"></div>
        </div>

        <div class="detail-group mb-4">
            <label class="text-muted-small d-block mb-1">Content</label>
            <div id="detail-content" class="mail-content-box"></div>
        </div>

        <div class="detail-group mb-2">
            <label class="text-muted-small d-block mb-1">📎 Attachments</label>
            <div id="detail-attachments" class="mail-attachments-box"></div>
        </div>

        <div class="text-end mt-4">
            <button onclick="$('#mailDetailModal').fadeOut()" class="btn btn-secondary px-4">Close Settings</button>
        </div>
    </div>
</div>

<script>
    $('#link-mail-logs').addClass('active');

    function openLogDetail(log) {
        $('#detail-subject').text(log.subject);
        
        // Format recipients: split by comma and join with newline
        var formattedRecipients = log.recipient.split(',').map(s => s.trim()).filter(s => s).join('\n');
        $('#detail-recipients').html(formattedRecipients.replace(/\n/g, '<br>'));
        
        // Fix for content showing tags: ensure it's rendered as HTML
        // If the data was saved escaped, we might need to decode it once or just use .html()
        // We'll use a temporary element to decode entities if they exist.
        var decodeEntity = function(str) {
            var textarea = document.createElement("textarea");
            textarea.innerHTML = str;
            return textarea.value;
        };
        
        var content = log.content;
        // If it looks like escaped HTML (contains &lt;), decode it
        if (content.indexOf('&lt;') !== -1) {
            content = decodeEntity(content);
        }
        $('#detail-content').html(content); 
        
        // Attachments: split by comma and show line by line with icons
        if (log.attachments && log.attachments.trim() !== '') {
            var attList = log.attachments.split(',').map(s => s.trim()).filter(s => s);
            var attHtml = attList.map(name => '<div class="mb-1"><i class="fa-solid fa-file-arrow-down me-2"></i>' + name + '</div>').join('');
            $('#detail-attachments').html(attHtml);
        } else {
            $('#detail-attachments').html('<div class="text-muted">No attachments</div>');
        }

        $('#mailDetailModal').fadeIn();
    }
</script>

<?php include_admin_footer(); ?>
