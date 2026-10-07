document.addEventListener('DOMContentLoaded', () => {
    const formProfile = document.getElementById('form_update_profile');
    const formBank = document.getElementById('form_update_bank');

    if (formProfile) {
        formProfile.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(formProfile);

            try {
                const response = await fetch('controller/profile.php?action=update_profile', {
                    method: 'POST',
                    body: formData
                });
                const res = await response.json();

                if (res.success) {
                    window.location.href = 'profile.php';
                } else {
                    alert(res.error || 'Failed to update profile.');
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while updating profile.');
            }
        });
    }

    if (formBank) {
        formBank.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(formBank);

            try {
                const response = await fetch('controller/profile.php?action=update_bank', {
                    method: 'POST',
                    body: formData
                });
                const res = await response.json();

                if (res.success) {
                    window.location.href = 'profile.php';
                } else {
                    alert(res.error || 'Failed to update bank details.');
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while updating bank details.');
            }
        });
    }
});