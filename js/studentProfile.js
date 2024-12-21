
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default anchor behavior
                const dropdownContent = this.nextElementSibling; // Get the associated dropdown content
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            });
        });

        // Close dropdowns if clicking outside
        window.addEventListener('click', function(event) {
            dropdownToggles.forEach(toggle => {
                const dropdownContent = toggle.nextElementSibling;
                if (!event.target.matches('.dropdown-toggle') && dropdownContent.style.display === 'block') {
                    dropdownContent.style.display = 'none';
                }
            });
        });
    });
    