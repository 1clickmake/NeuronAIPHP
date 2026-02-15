/**
 * Breeze Template Custom Script
 */
console.log('Breeze template loaded with dynamic asset system!');

$(document).ready(function() {
    // Breeze specific interaction
    $('.navbar-brand').on('mouseover', function() {
        $(this).css('text-shadow', '0 0 20px var(--primary)');
    }).on('mouseout', function() {
        $(this).css('text-shadow', 'none');
    });
});
