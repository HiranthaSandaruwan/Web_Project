		</div> <!-- Close page-content -->
	</main> <!-- Close main-content -->
</div> <!-- Close app-layout -->

<!-- Footer -->
<div class="footer">
	<div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
		<span>🔧</span>
		<span>&copy; 2025 Hardware Request Manager - Built with ❤️</span>
	</div>
	<div style="margin-top: 8px; font-size: 12px; color: var(--text-muted);">
		<span>💡 Press <kbd>Ctrl+D</kbd> for dark mode • <kbd>Ctrl+B</kbd> for sidebar</span>
	</div>
</div>

<style>
/* Mobile menu toggle visibility */
@media (max-width: 768px) {
	#mobile-menu-toggle { display: block !important; }
}

/* Keyboard shortcut display */
kbd {
	background: var(--bg-tertiary);
	border: 1px solid var(--border-medium);
	border-radius: 3px;
	padding: 2px 4px;
	font-size: 11px;
	font-family: monospace;
}
</style>

<script>
// Initialize theme from localStorage on page load
(function() {
	const savedTheme = localStorage.getItem('theme');
	if (savedTheme) {
		document.documentElement.setAttribute('data-theme', savedTheme);
	}
})();
</script>

</body>
</html>
