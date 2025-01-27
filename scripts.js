document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.tab-button');
    const panels = document.querySelectorAll('.tab-panel');
    const debugToggle = document.getElementById('debug-toggle');
    const debugBox = document.getElementById('debug-box');
    const debugOutput = document.getElementById('debug-output');

    // Tab Switching Logic
    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            panels.forEach(p => p.style.display = 'none');
            
            tab.classList.add('active');
            panels[index].style.display = 'block';
        });
    });

    // Debug Mode Toggle
    debugToggle.addEventListener('click', () => {
        debugBox.style.display = debugBox.style.display === 'none' ? 'block' : 'none';
    });

    // Fetch Online Players
    const fetchPlayers = () => {
        fetch('api.php?action=online_players')
            .then(response => response.json())
            .then(data => {
                const playersList = document.getElementById('players-list');
                playersList.innerHTML = '';

                data.players.forEach(player => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <span title="Unique ID: ${player.unique_id}">${player.name}</span>
                        <div>
                            <button onclick="kickPlayer('${player.unique_id}')">Kick</button>
                            <button onclick="banPlayer('${player.unique_id}')">Ban</button>
                        </div>
                    `;
                    playersList.appendChild(li);
                });

                updateDebugOutput(data);
            });
    };

    // Fetch Banned Players
    const fetchBannedPlayers = () => {
        fetch('api.php?action=banned_players')
            .then(response => response.json())
            .then(data => {
                const bannedList = document.getElementById('banned-list');
                bannedList.innerHTML = '';

                data.banned.forEach(player => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <span title="Unique ID: ${player.unique_id}">${player.name}</span>
                        <button onclick="unbanPlayer('${player.unique_id}')">Unban</button>
                    `;
                    bannedList.appendChild(li);
                });

                updateDebugOutput(data);
            });
    };

    // Update Debug Output
    const updateDebugOutput = (data) => {
        debugOutput.innerText = JSON.stringify(data, null, 2); // Preserve indentation
    };

    // Ajax Refresh after actions
    const kickPlayer = (uniqueId) => {
        fetch(`api.php?action=kick&unique_id=${uniqueId}`)
            .then(response => response.json())
            .then(data => {
                alert(data.message || `Player ${uniqueId} kicked!`);
                fetchPlayers(); // Refresh player list
            })
            .catch(console.error);
    };

    const banPlayer = (uniqueId) => {
        fetch(`api.php?action=ban&unique_id=${uniqueId}`)
            .then(response => response.json())
            .then(data => {
                alert(data.message || `Player ${uniqueId} banned!`);
                fetchPlayers(); // Refresh player list
                fetchBannedPlayers(); // Refresh banned list
            })
            .catch(console.error);
    };

    const unbanPlayer = (uniqueId) => {
        fetch(`api.php?action=unban&unique_id=${uniqueId}`)
            .then(response => response.json())
            .then(data => {
                alert(data.message || `Player ${uniqueId} unbanned!`);
                fetchBannedPlayers(); // Refresh banned list
            })
            .catch(console.error);
    };

    // Periodically refresh the lists
    setInterval(fetchPlayers, 3000);
    fetchPlayers(); // Initial fetch for online players
    fetchBannedPlayers(); // Initial fetch for banned players

    // Expose functions to global scope for onclick handlers
    window.kickPlayer = kickPlayer;
    window.banPlayer = banPlayer;
    window.unbanPlayer = unbanPlayer;
});
