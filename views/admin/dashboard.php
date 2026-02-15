<?php $title = 'Admin Dashboard'; include_admin_header($title); ?>

<div class="glass-card">
    <h1>Dashboard</h1>
    <p class="text-muted-small" style="margin-top: 1rem;">Welcome to the Neuron AI Administration Panel. Manage your members and board configurations here.</p>
    
    <div class="admin-grid" style="margin-top: 3rem;">
        <div class="glass-card" style="margin-bottom: 0; text-align: center;">
            <h3 class="text-primary-weight" style="font-size: 2rem;">Admin</h3>
            <p>System Status</p>
        </div>
        <div class="glass-card" style="margin-bottom: 0; text-align: center;">
            <h3 class="text-primary-weight" style="font-size: 2rem;">PHP 8</h3>
            <p>Environment</p>
        </div>
        <div class="glass-card" style="margin-bottom: 0; text-align: center;">
            <h3 class="text-primary-weight" style="font-size: 2rem;">MySQL</h3>
            <p>Database</p>
        </div>
    </div>
</div>

<script>
    $('#link-dashboard').addClass('active');
</script>

<?php include_admin_footer(); ?>
