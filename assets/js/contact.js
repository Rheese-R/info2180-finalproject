
const contactMessage = document.getElementById('contact-message');
const contactDetails = document.getElementById('contact-details');
const contactActionMessage = document.getElementById('contact-action-message');

const contactName = document.getElementById('contact-name');
const contactType = document.getElementById('contact-type');
const contactEmail = document.getElementById('contact-email');
const contactTelephone = document.getElementById('contact-telephone');
const contactCompany = document.getElementById('contact-company');
const contactAssigned = document.getElementById('contact-assigned');
const contactCreatedBy = document.getElementById('contact-created-by');
const contactCreatedAt = document.getElementById('contact-created-at');
const contactUpdatedAt = document.getElementById('contact-updated-at');

const assignContactButton = document.getElementById('assign-contact-button');
const changeTypeButton = document.getElementById('change-type-button');

// Get the contact ID from the page URL.
const urlParameters = new URLSearchParams(window.location.search);
const contactId = urlParameters.get('id');

// Format database dates for display.
function formatContactDate(dateValue) {
    if (!dateValue) {
        return 'Not available';
    }

    const date = new Date(dateValue.replace(' ', 'T'));

    if (Number.isNaN(date.getTime())) {
        return dateValue;
    }

    return date.toLocaleString();
}

// Retrieve and display the contact details.
async function loadContact() {
    if (!contactId || !/^[1-9]\d*$/.test(contactId)) {
        contactMessage.textContent = 'Invalid contact ID.';
        return;
    }

    contactMessage.textContent = 'Loading contact details...';
    contactDetails.hidden = true;

    try {
        const response = await fetch(
            'api/contact.php?id=' + encodeURIComponent(contactId)
        );

        const result = await response.json();

        if (!response.ok || !result.success) {
            contactMessage.textContent =
                result.message || 'Unable to load contact details.';
            return;
        }

        const contact = result.contact;

        contactName.textContent =
            contact.title + ' ' +
            contact.firstname + ' ' +
            contact.lastname;

        contactType.textContent = contact.type;
        contactEmail.textContent = contact.email;
        contactTelephone.textContent = contact.telephone;
        contactCompany.textContent = contact.company;

        contactAssigned.textContent =
            contact.assigned_firstname + ' ' +
            contact.assigned_lastname;

        contactCreatedBy.textContent =
            contact.creator_firstname + ' ' +
            contact.creator_lastname;

        contactCreatedAt.textContent =
            formatContactDate(contact.created_at);

        contactUpdatedAt.textContent =
            formatContactDate(contact.updated_at);

        contactMessage.textContent = '';
        contactDetails.hidden = false;

        // Remember the current type and show the next available action.
        changeTypeButton.dataset.currentType = contact.type;

        changeTypeButton.textContent =
            contact.type === 'Sales Lead'
                ? 'Change to Support'
                : 'Change to Sales Lead';

    } catch (error) {
        contactMessage.textContent =
            'Unable to connect to the server. Please try again.';
    }
}

// Assign the contact to the logged-in user.
assignContactButton.addEventListener('click', async function () {
    assignContactButton.disabled = true;
    contactActionMessage.textContent = 'Assigning contact...';

    const formData = new FormData();
    formData.append('contact_id', contactId);

    try {
        const response = await fetch('api/assign-contact.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            contactActionMessage.textContent =
                result.message || 'Unable to assign contact.';
            return;
        }

        contactActionMessage.textContent = result.message;

        // Refresh the displayed contact information.
        await loadContact();

    } catch (error) {
        contactActionMessage.textContent =
            'Unable to connect to the server. Please try again.';
    } finally {
        assignContactButton.disabled = false;
    }
});

// Switch the contact between Sales Lead and Support.
changeTypeButton.addEventListener('click', async function () {
    const currentType = changeTypeButton.dataset.currentType;

    const newType = currentType === 'Sales Lead'
        ? 'Support'
        : 'Sales Lead';

    changeTypeButton.disabled = true;
    contactActionMessage.textContent = 'Updating contact type...';

    const formData = new FormData();
    formData.append('contact_id', contactId);
    formData.append('type', newType);

    try {
        const response = await fetch('api/change-contact-type.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            contactActionMessage.textContent =
                result.message || 'Unable to change contact type.';
            return;
        }

        contactActionMessage.textContent = result.message;

        // Reload the details without refreshing the page.
        await loadContact();

    } catch (error) {
        contactActionMessage.textContent =
            'Unable to connect to the server. Please try again.';
    } finally {
        changeTypeButton.disabled = false;
    }
});

// Load the contact when the page opens.
loadContact();
