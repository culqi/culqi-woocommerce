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
// document.addEventListener('DOMContentLoaded', () => {

const previewCustomFunction = (insertPalette = '', insertLogo = '') => {
  const setLogoPs = insertLogo; // Al iniciar estará vacío, una vez personalizado el checkout esta variable tendrá seteado el url de logo ingresado
  const setPrimaryColor = ''; // Al iniciar estará vacío, una vez personalizado el checkout esta variable tendrá seteado el color ingresado
  const setSecondaryColor = ''; // Al iniciar estará vacío, una vez personalizado el checkout esta variable tendrá seteado el color ingresado
  const setPalette = insertPalette;

  const setColors = (setPrimaryColor != '' ? setPrimaryColor.slice(1) : '') + '-' + (setSecondaryColor != '' ? setSecondaryColor.slice(1) : '');

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
  const logo_default = document.querySelector('#logo-default');
  const labelText = document.querySelector('#label-text');

  const btnClose = document.querySelectorAll('#btn-close')
  const btnSave = document.querySelector('#btn-save')

  const checkoutPreviewText = {
    labelDefault: 'Copia la URL de tu logotipo',
    logoDefault: logo_default.src,
    errors: {
      logoUrl: 'URL incompleta, falta agregar protocolo https o http',
      logoInvalid: 'Imagen no válida'
    }
  }

  let styleConfig = {};
  let isEnabledRadio = false,
    isEnabledLogo = false,
    isValidUrlLogo = true;

  const actionButton = () => {
    if (isEnabledRadio || isEnabledLogo) {
      btnSave.disabled = false;
      btnSave.classList.remove('disabled')
    } else {
      btnSave.classList.add('disabled')
      btnSave.disabled = true;
    }
  }

  const getSetColor = () => {
    let color = '';
    if (styleConfig.primaryColor && styleConfig.secondaryColor) {
      if (styleConfig.primaryColor != '' && styleConfig.secondaryColor != '') {
        color = styleConfig.primaryColor.slice(1) + '-' + styleConfig.secondaryColor.slice(1);
      }
    } else if (setPalette != '') {
      color = setPalette.replace(new RegExp('#', 'g'), '');
    }
    return color;
  }

  const setValidationInput = (input = null, logo = null) => {
    if (typeof input == "boolean") {
      isEnabledRadio = input
    }
    if (typeof logo == "boolean") {
      isEnabledLogo = logo
    }
  }

  const configColors = (palette, isRemove) => {
    isRemove = isRemove || false;
    paletteLeft.style.background = isRemove ? null : '#' + palette[0];;
    paletteRight.forEach((item) => {
      switch (item.getAttribute('name')) {
        case 'color':
          item.style.color = isRemove ? null : '#' + palette[1];
          break;
        case 'svg':
          item.style.fill = isRemove ? null : '#' + palette[1];
          break;
        case 'bg':
          item.style.background = isRemove ? null : '#' + palette[1];
          break;
        default:
          break;
      }
    });
  };

  if (setLogoPs != '' && setLogoPs != null && setLogoPs != undefined) {
    inputLogo.src = setLogoPs;
  }

  if (setColors != '' && setColors != null && setColors != undefined) {
    inputColor.forEach(el => {
      if (el.id == setColors) {
        el.checked = true
        configColors(setColors.split('-'))
      }
    })
  };

  inputColor.forEach(el => {
    const colors = el.id.split('-');
    const lbl = el.nextElementSibling;
    const left = lbl.querySelector('.color-container__left');
    const right = lbl.querySelector('.color-container__right');
    left.style.background = '#' + colors[0];
    right.style.background = '#' + colors[1];
    el.addEventListener('change', e => {
      const palette = e.target.id.split('-');
      setValidationInput(isValidUrlLogo ? (e.target.id !== getSetColor()) : false, null)
      configColors(palette);
      actionButton();
    })
  })

  inputLogo.addEventListener('input', el => {
    const r = /^(http|https):\/\/[^ "]+$/
    if (r.test(el.target.value)) {
      let image = new Image();
      image.src = el.target.value;

      image.addEventListener('error', () => {
        setValidationInput(false, false);
        inputLogo.classList.add('invalid')
        isValidUrlLogo = false;
        labelText.style.color = '#D20808';
        labelText.innerText = checkoutPreviewText.errors.logoInvalid;
        actionButton();
      });

      image.addEventListener('load', () => {
        if (styleConfig.urlLogo) {
          if (styleConfig.urlLogo == el.target.value) {
            setValidationInput(null, false);
          }
        } else if (setLogoPs == el.target.value) {
          setValidationInput(null, false);
        } else {
          setValidationInput(true, true);
        }
        logo.src = el.target.value;
        labelText.innerText = checkoutPreviewText.labelDefault;
        isValidUrlLogo = true;
        inputLogo.classList.remove('invalid');
        labelText.removeAttribute('style');
        actionButton();
      });
    } else if (el.target.value == '') {
      labelText.innerText = checkoutPreviewText.labelDefault;
      inputLogo.classList.remove('invalid');
      labelText.removeAttribute('style');
      logo.src = checkoutPreviewText.logoDefault;
      if (styleConfig.urlLogo) {
        if (styleConfig.urlLogo == '') {
          setValidationInput(true, false);
        } else {
          setValidationInput(null, true);
        }
      } else if (setLogoPs == '') {
        setValidationInput(true, false);
      } else {
        setValidationInput(true, true);
      }
      isValidUrlLogo = true;
      actionButton();
    } else {
      isValidUrlLogo = false;
      setValidationInput(false, false);
      inputLogo.classList.add('invalid')
      labelText.style.color = '#D20808';
      labelText.innerText = checkoutPreviewText.errors.logoUrl;
      actionButton();
    }
  });

  action.addEventListener('click', () => {
    actionSvg.classList.toggle('rotate');
    actionContainer.classList.toggle('active');
    // cambiar texto de un span
    if (actionContainer.classList.contains('active')) {
      actionTextVisible.innerText = 'Ver menos combinaciones';
    } else {
      actionTextVisible.innerText = 'Ver más combinaciones';
    }
  });

  btnSave.addEventListener('click', () => {
    const inputs = personalize.getElementsByTagName('input');
    Array.from(inputs).forEach(e => {
      if (e.name == 'color-palette') {
        if (e.checked) {
          const colors = e.id.split('-');
          styleConfig.primaryColor = '#' + colors[0];
          styleConfig.secondaryColor = '#' + colors[1];
        }
      } else {
        styleConfig.urlLogo = e.value;
      }
    })
    overlay.classList.remove('active');
    jQuery('#fullculqi_logo').val(styleConfig.urlLogo)
    jQuery('#fullculqi_colorpalette').val(styleConfig.primaryColor + '-' + styleConfig.secondaryColor);
  });

  Array.from(btnClose).forEach((el) => {
    el.addEventListener('click', (event) => {
      if (styleConfig.urlLogo != undefined) {
        if (styleConfig.urlLogo != '' && styleConfig.urlLogo != null && styleConfig.urlLogo != undefined) {
          inputLogo.value = styleConfig.urlLogo;
          logo.src = styleConfig.urlLogo;
        } else {
          inputLogo.value = '';
          logo.src = checkoutPreviewText.logoDefault;
        }
      } else {
        if (setLogoPs != '' && setLogoPs != null && setLogoPs != undefined) {
          inputLogo.value = setLogoPs;
          logo.src = setLogoPs;
        } else {
          logo.src = checkoutPreviewText.logoDefault;
          inputLogo.value = '';
        }
      }
      labelText.innerText = checkoutPreviewText.labelDefault;
      inputLogo.classList.remove('invalid');
      labelText.removeAttribute('style');
      if (styleConfig.primaryColor && styleConfig.secondaryColor) {
        document.getElementById(styleConfig.primaryColor.slice(1) + '-' + styleConfig.secondaryColor.slice(1)).click()
        configColors([styleConfig.primaryColor, styleConfig.secondaryColor]);
      } else {
        if (setPalette != '' && setPalette != null && setPalette != undefined) {
          document.getElementById(setPalette.replace(new RegExp(/\#/g), '')).click()
        } else {
          configColors(setPalette.split('-'), true);

        }
      }
      setValidationInput(false, false)
      overlay.classList.remove('active');
    });
  });

  btnOpen.addEventListener('click', () => {
    setValidationInput(false, false)
    actionButton();
    if (styleConfig.urlLogo != undefined) {
      if (styleConfig.urlLogo != '' && styleConfig.urlLogo != null && styleConfig.urlLogo != undefined) {
        inputLogo.value = styleConfig.urlLogo;
        logo.src = styleConfig.urlLogo;
      } else {
        inputLogo.value = '';
        logo.src = checkoutPreviewText.logoDefault;
      }
    } else {
      if (setLogoPs != '' && setLogoPs != null && setLogoPs != undefined) {
        inputLogo.value = setLogoPs;
        logo.src = setLogoPs;
      } else {
        logo.src = checkoutPreviewText.logoDefault;
        inputLogo.value = '';
      }
    }
    if (styleConfig.primaryColor && styleConfig.secondaryColor) {
      configColors([styleConfig.primaryColor, styleConfig.secondaryColor]);
    } else {
      if (setPalette != '' && setPalette != null && setPalette != undefined) {
        configColors(setPalette.replace(new RegExp(/\#/g), "").split('-'));
      } else {
        document.querySelector(".colorPreviewDefault").click()
        configColors(['', ''], true);
      }
    }
    overlay.classList.add('active')
  });
}
// })