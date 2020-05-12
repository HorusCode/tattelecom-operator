// Collapsible lists
import anime from 'animejs';

window.onload = () => {
  let $verticalListTrigger = $('.vertical-list__trigger');

  let activeItems = $('.vertical-list__item .submenu .active').length > 0;
  activeItems ? $verticalListTrigger.addClass('active') : '';

  $verticalListTrigger.on('click', function() {
    let subMenu = this.nextElementSibling;
    this.querySelector('.mdi-chevron-up').classList.toggle('mdi-rotate-180');
    let arr =
        subMenu.clientHeight === subMenu.scrollHeight
            ? [subMenu.scrollHeight, 0]
            : [0, subMenu.scrollHeight];
    anime({
      targets: subMenu,
      height: arr,
      easing: 'easeOutQuad',
      duration: 200,
    });
  });

  $('#app').on('click', '.burger, .burger > span', function() {
    $('.burger').toggleClass('active');
    let $sidebar = $('.sidebar')[0];
    $sidebar.classList.toggle('close');

    let logout = document.getElementById('logout');
    if (logout.classList.contains('is-extended')) {
      logout.classList.replace('btn-primary', 'btn-primary-text');
      logout.classList.replace('is-extended', 'btn-icon');
    }
    else {
      logout.classList.replace('btn-icon', 'is-extended');
      logout.classList.replace('btn-primary-text', 'btn-primary');
    }
  });
};
