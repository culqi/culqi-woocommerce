/**
 * @license
 * three.js - JavaScript 3D library
 * Copyright 2016 The three.js Authors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
document.addEventListener('DOMContentLoaded', () => {
  const setUrlLogo = ''; // Al iniciar estará vacío, una vez personalizado el checkout esta variable tendrá seteado el url de logo ingresado
  const setPrimaryColor = ''; // Al iniciar estará vacío, una vez personalizado el checkout esta variable tendrá seteado el color ingresado
  const setSecondaryColor = ''; // Al iniciar estará vacío, una vez personalizado el checkout esta variable tendrá seteado el color ingresado
  const setColors = `${setPrimaryColor != '' ? setPrimaryColor.slice(1) : ''}-${setSecondaryColor != '' ? setSecondaryColor.slice(1) : ''}`;

  const btnOpen = document.querySelector('#open-modal')
  const overlay = document.querySelector('#overlay')

  const personalize = document.querySelector('#personalize');

  const inputColor = document.getElementsByName('color-palette');
  const paletteLeft = document.querySelector('#palette-left')
  const paletteRight = document.querySelectorAll('#palette-right');
  const action = document.querySelector('.action-visible');
  const actionSvg = document.querySelector('.action-svg');
  const actionContainer = document.querySelector('.action-container');
  const actionTextVisible = document.querySelector('.action-visible-text');
  const inputLogo = document.querySelector('#logo-url');
  const logo = document.querySelector('#logo');
  const labelText = document.querySelector('#label-text');

  const btnClose = document.querySelectorAll('#btn-close')
  const btnSave = document.querySelector('#btn-save')

  const logoDefault = logo.src;
  const labelDefault = 'Copia la URL de tu logotipo';

  let styleConfig = {};
  let isSelectRadios = false,
      isUrlDefault = false;

  const actionButton = () => {
    if (isSelectRadios || isUrlDefault) {
      btnSave.disabled = false;
      btnSave.classList.remove('disabled')
    } else {
      btnSave.classList.add('disabled')
      btnSave.disabled = true;
    }
  }
  const configColors = palette => {
    paletteLeft.style.background = `#${palette[0]}`;
    paletteRight.forEach(item => {
      switch (item.getAttribute('name')) {
        case 'color':
          item.style.color = `#${palette[1]}`;
          break;
        case 'svg':
          item.style.fill = `#${palette[1]}`;
          break;
        case 'bg':
          item.style.background = `#${palette[1]}`;
          break;
        default:
          break;
      }
    })
  }

  if (setUrlLogo != '' && setUrlLogo != null && setUrlLogo != undefined) {
    inputLogo.src = setColors;
  }
  if (setColors != '' && setColors != null && setColors != undefined) {
    inputColor.forEach(el => {
      if (el.id == setColors) {
        el.checked = true
        configColors(setColors.split('-'))
      }
    })
  }
  ;

  inputColor.forEach(el => {
    const colors = el.id.split('-');
    const lbl = el.nextElementSibling;
    const left = lbl.querySelector('.color-container__left');
    const right = lbl.querySelector('.color-container__right');
    left.style.background = `#${colors[0]}`;
    right.style.background = `#${colors[1]}`;
    el.addEventListener('change', e => {
      const palette = e.target.id.split('-');
      isSelectRadios = (e.target.id !== setColors);
      configColors(palette);
      actionButton();
    })
  })
  inputLogo.addEventListener('input', el => {
    const r = /^(http|https):\/\/[^ "]+$/
    if (r.test(el.target.value)) {
      logo.src = el.target.value;
      isUrlDefault = true;
      labelText.innerText = labelDefault;
      inputLogo.classList.remove('invalid');
      labelText.removeAttribute('style');
    } else if (el.target.value == '') {
      labelText.innerText = labelDefault;
      inputLogo.classList.remove('invalid');
      labelText.removeAttribute('style');
    } else {
      inputLogo.classList.add('invalid')
      labelText.style.color = '#D20808';
      isUrlDefault = false;
      logo.src = logoDefault;
      labelText.innerText = 'URL incompleta, falta agregar protocolo https o http';
    }
    ;
    actionButton();
  });
  action.addEventListener('click', () => {
    actionSvg.classList.toggle('rotate');
    actionContainer.classList.toggle('active');
    // cambiar texto de un span
    if (actionContainer.classList.contains('active')) {
      actionTextVisible.innerText = 'Ver menos combinaciones';
    }
    else {
      actionTextVisible.innerText = 'Ver más combinaciones';
    }
  });
  btnSave.addEventListener('click', () => {
    const inputs = personalize.getElementsByTagName('input');
    Array.from(inputs).forEach(e => {
      if (e.name == 'color-palette') {
        if (e.checked) {
          const colors = e.id.split('-');
          styleConfig.primaryColor = `#${colors[0]}`;
          styleConfig.secondaryColor = `#${colors[1]}`;
        }
      } else {
        styleConfig.urlLogo = e.value;
      }
    })
    overlay.classList.remove('active');
    jQuery('#fullculqi_logo').val(styleConfig.urlLogo);
    jQuery('#fullculqi_colorpalette').val(styleConfig.primaryColor+'-'+styleConfig.secondaryColor);
    console.log('styleConfig: ', styleConfig)
  });
  Array.from(btnClose).forEach(el => {
    el.addEventListener('click', event => {
      overlay.classList.remove('active')
    });
  });
  btnOpen.addEventListener('click', () => {
    overlay.classList.add('active')
  });
})