document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.group-tab');
    const panels = document.querySelectorAll('.group-panel');

    if (!tabs.length) return;

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Get the panel id from the tab id: "tab-balances" -> "panel-balances"
            const panelId = 'panel-' + tab.id.replace('tab-', '');
            const targetPanel = document.getElementById(panelId);

            // Deactivate all tabs
            tabs.forEach(t => {
                t.classList.remove('border-black', 'text-black');
                t.classList.add('border-transparent', 'text-gray-400');
                // Update badge to inactive color
                const badge = t.querySelector('span');
                if (badge) {
                    badge.classList.remove('bg-black');
                    badge.classList.add('bg-gray-400');
                }
            });

            // Activate clicked tab
            tab.classList.remove('border-transparent', 'text-gray-400');
            tab.classList.add('border-black', 'text-black');
            const activeBadge = tab.querySelector('span');
            if (activeBadge) {
                activeBadge.classList.remove('bg-gray-400');
                activeBadge.classList.add('bg-black');
            }

            // Hide all panels, show the target
            panels.forEach(p => p.classList.add('hidden'));
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
            }
        });
    });
});
