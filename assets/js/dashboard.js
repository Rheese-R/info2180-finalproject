const contactsTableBody = document.getElementById('contacts-table-body');
const contactsMessage = document.getElementById('contacts-message');
const filterButtons = document.querySelectorAll('.filter-button');

// Load contacts using the selected filter.
async function loadContacts(filter = 'all') {
    contactsMessage.textContent = 'Loading contacts...';
    contactsTableBody.replaceChildren();

    try {
        const response = await fetch(
            'api/contacts.php?filter=' + encodeURIComponent(filter)
        );

        const result = await response.json();

        if (!response.ok || !result.success) {
            contactsMessage.textContent =
                result.message || 'Unable to load contacts.';
            return;
        }

        if (result.contacts.length === 0) {
            contactsMessage.textContent =
                'No contacts found for this filter.';
            return;
        }

        contactsMessage.textContent = '';

        result.contacts.forEach(function (contact) {
            const row = document.createElement('tr');

            // Contact name.
            const nameCell = document.createElement('td');
            const nameLink = document.createElement('a');

            nameLink.href = 'contact.php?id=' +
                encodeURIComponent(contact.id);

            nameLink.textContent =
                contact.title + ' ' +
                contact.firstname + ' ' +
                contact.lastname;

            nameCell.appendChild(nameLink);

            // Contact email.
            const emailCell = document.createElement('td');
            emailCell.textContent = contact.email;

            // Contact company.
            const companyCell = document.createElement('td');
            companyCell.textContent = contact.company;

            // Contact type.
            const typeCell = document.createElement('td');
            typeCell.textContent = contact.type;

            row.appendChild(nameCell);
            row.appendChild(emailCell);
            row.appendChild(companyCell);
            row.appendChild(typeCell);

            contactsTableBody.appendChild(row);
        });

    } catch (error) {
        contactsMessage.textContent =
            'Unable to connect to the server. Please try again.';
    }
}

// Add click events to the dashboard filter buttons.
filterButtons.forEach(function (button) {
    button.addEventListener('click', function () {
        const selectedFilter = button.dataset.filter;

        // Update the active filter button.
        filterButtons.forEach(function (filterButton) {
            filterButton.classList.remove('active');
        });

        button.classList.add('active');

        // Load contacts matching the selected filter.
        loadContacts(selectedFilter);
    });
});

// Load all contacts when the dashboard first opens.
loadContacts();