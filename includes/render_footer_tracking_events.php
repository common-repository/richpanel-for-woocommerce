<?php if ($this->has_events_in_cookie) : ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
$.ajax({
url: "<?php echo esc_url(admin_url('admin-ajax.php')); ?>",
type: 'GET',
data: {
action: 'richpanel_clear_events',
},
success: function(response) {
// Handle the success response
console.log('Events cleared successfully!');
},
error: function(xhr, status, error) {
// Handle the error response
console.log('Error clearing events:', error);
}
});
});
</script>
<?php endif; ?>

