var isMenuVisible = false;
var sidebar = document.getElementById('sidebar');

function toggleMenu() {
    isMenuVisible = !isMenuVisible;
    if (isMenuVisible) {
        sidebar.classList.add('active');
    } else {
        sidebar.classList.remove('active');
    }
}

document.body.addEventListener("click", (event) => {
    const clickedElement = event.target;
    if (!clickedElement.closest("#top-nav")) {
        if (isMenuVisible) {
            if (!clickedElement.closest("#sidebar")) {
                sidebar.classList.remove('active');
                isMenuVisible = false;
            }
        }
    }
});

function onScroll() {
    var topNav = document.getElementById('top-nav');
    var scrollY = window.scrollY || window.pageYOffset;
    if (scrollY > 0) {
        topNav.classList.add('fixed');
    } else {
        topNav.classList.remove('fixed');
    }
}

// Attach the scroll event listener
window.addEventListener('scroll', onScroll);

// Notification

var iphost = "http://localhost/www.indang-municipal-hr.com.ph";
$(document).ready(function () {
    function fetchNotifications() {
        $.ajax({
            url: iphost + '/actions/fetchNotification.php',
            method: 'POST', // Change the method to POST
            success: function (data) {
                $('#notification-container').html(data);
            }
        });
    }

    function fetchNotificationsCount() {
        $.ajax({
            url: iphost + '/actions/fetchNotificationCount.php',
            method: 'POST',
            success: function (data) {
                $('#notifCount').html(data);
            }
        });
    }

    function markNotificationsAsSeen() {
        $.ajax({
            url: iphost + '/actions/markNotificationsAsSeen.php',
            method: 'POST',
            success: function (data) {
            }
        });
    }

    $('#notification-menu').click(function (event) {
        $('#notification-container').toggleClass('show');

        $('#notifCount').html(0);
        markNotificationsAsSeen();

        event.stopPropagation();
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('#notification-container, #notification-menu').length) {
            $('#notification-container').removeClass('show');
        }
    });

    setInterval(fetchNotifications, 3000);
    setInterval(fetchNotificationsCount, 3000);

    fetchNotifications();
    fetchNotificationsCount();
});