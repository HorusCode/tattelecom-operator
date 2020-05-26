// Collapsible lists
import anime from 'animejs';

window.onload = () => {
  let $verticalListTrigger = $('.vertical-list__trigger');

  let activeItems = $('.vertical-list__item .submenu .active').length > 0;
  if(activeItems) {
    $verticalListTrigger.addClass('active');
    $verticalListTrigger.next().toggleClass('active');
    $verticalListTrigger.find('.mdi-chevron-up').toggleClass('mdi-rotate-180');
  }

  $verticalListTrigger.on('click', function() {
    this.nextElementSibling.classList.toggle('active');
    this.querySelector('.mdi-chevron-up').classList.toggle('mdi-rotate-180');
    /*let arr =
        subMenu.clientHeight === subMenu.scrollHeight
            ? [subMenu.scrollHeight, 0]
            : [0, subMenu.scrollHeight];
    anime({
      targets: subMenu,
      height: arr,
      easing: 'easeOutQuad',
      duration: 200,
    });*/
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

  $('.password-eye').on('click', function() {
    let $input = $("input[name='password']")[0];
    $input.type = $input.type === 'password' ? 'text' : 'password';
    $(this).children('.mdi').changeClass('mdi-eye-outline', 'mdi-eye-off-outline');
  });

};
