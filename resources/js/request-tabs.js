document.addEventListener('DOMContentLoaded', () => {
    const tabSent = document.getElementById('tab-sent');
    const tabReceived = document.getElementById('tab-received');
    const panelSent = document.getElementById('panel-sent');
    const panelReceived = document.getElementById('panel-received');

    if (!tabSent || !tabReceived) return;

    function activateTab(activeTab, activePanel, inactiveTab, inactivePanel) {
        // Active tab styles
        activeTab.classList.remove('border-transparent', 'text-gray-400');
        activeTab.classList.add('border-black', 'text-black');

        // Inactive tab styles
        inactiveTab.classList.remove('border-black', 'text-black');
        inactiveTab.classList.add('border-transparent', 'text-gray-400');

        // Update badge colors
        const activeBadge = activeTab.querySelector('span');
        const inactiveBadge = inactiveTab.querySelector('span');
        if (activeBadge) {
            activeBadge.classList.remove('bg-gray-400');
            activeBadge.classList.add('bg-black');
        }
        if (inactiveBadge) {
            inactiveBadge.classList.remove('bg-black');
            inactiveBadge.classList.add('bg-gray-400');
        }

        // Toggle panels
        activePanel.classList.remove('hidden');
        inactivePanel.classList.add('hidden');
    }

    tabSent.addEventListener('click', () => {
        activateTab(tabSent, panelSent, tabReceived, panelReceived);
    });

    tabReceived.addEventListener('click', () => {
        activateTab(tabReceived, panelReceived, tabSent, panelSent);
    });
});
