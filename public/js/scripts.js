/*!
* Start Bootstrap - Landing Page v6.0.6 (https://startbootstrap.com/theme/landing-page)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-landing-page/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project
document.addEventListener('DOMContentLoaded', () => {
        let dropdownToggle = document.getElementById('dropdownToggle');
        let dropdownMenu = document.getElementById('dropdownMenu');

        function toggleDropdown() {
            dropdownMenu.classList.toggle('hidden');
            dropdownMenu.classList.toggle('block');
        }

        function hideDropdown() {
            dropdownMenu.classList.add('hidden');
            dropdownMenu.classList.remove('block');
        }

        dropdownToggle.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevents triggering document click
            toggleDropdown();
        });

        // Hide dropdown when <li> is clicked
        dropdownMenu.querySelectorAll('.dropdown-item').forEach((li) => {
            li.addEventListener('click', () => {
                hideDropdown();
            });
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!dropdownMenu.contains(event.target) && event.target !== dropdownToggle) {
                hideDropdown();
            }
        });
    });
let subMenu = document.getElementsById("subMenu");
function toggleMenu(){
    subMenu.classList.toggle("open-menu");
}