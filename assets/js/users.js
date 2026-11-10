const usersTableBody = document.getElementById('users-table-body');
const usersMessage = document.getElementById('users-message');

async function loadUsers() {
    usersMessage.textContent = 'Loading users...';
    usersTableBody.replaceChildren();

    try {
        const response = await fetch('api/users.php', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            usersMessage.textContent =
                result.message || 'Unable to load users.';
            return;
        }

        if (result.users.length === 0) {
            usersMessage.textContent = 'No users have been registered yet.';
            return;
        }

        usersMessage.textContent = '';

        result.users.forEach(function (user) {
            const row = document.createElement('tr');

            const nameCell = document.createElement('td');
            nameCell.textContent =
                user.firstname + ' ' + user.lastname;

            const emailCell = document.createElement('td');
            emailCell.textContent = user.email;

            const roleCell = document.createElement('td');
            roleCell.textContent = user.role;

            const dateCell = document.createElement('td');

            const createdDate = new Date(
                user.created_at.replace(' ', 'T')
            );

            dateCell.textContent = Number.isNaN(createdDate.getTime())
                ? user.created_at
                : createdDate.toLocaleString();

            row.appendChild(nameCell);
            row.appendChild(emailCell);
            row.appendChild(roleCell);
            row.appendChild(dateCell);

            usersTableBody.appendChild(row);
        });

    } catch (error) {
        usersMessage.textContent =
            'Unable to connect to the server. Please try again.';
    }
}

loadUsers();