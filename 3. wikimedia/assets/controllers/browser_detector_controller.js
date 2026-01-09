import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['chrome', 'firefox', 'generic', 'label'];
  static values = {
    chromeUrl: String,
    firefoxUrl: String
  };

  connect() {
    const userAgent = navigator.userAgent.toLowerCase();
    const isFirefox = userAgent.indexOf('firefox') > -1;

    if (isFirefox) {
      this.setMode('firefox');
    } else {
      this.setMode('chrome'); // Chrome, Edge, Brave, etc.
    }
  }

  setMode(browser) {
    if (this.hasChromeTarget) this.chromeTarget.classList.add('hidden');
    if (this.hasFirefoxTarget) this.firefoxTarget.classList.add('hidden');
    if (this.hasGenericTarget) this.genericTarget.classList.add('hidden');

    if (browser === 'firefox') {
      this.element.href = this.firefoxUrlValue;
      if (this.hasFirefoxTarget) this.firefoxTarget.classList.remove('hidden');
      if (this.hasLabelTarget) this.labelTarget.textContent = "Añadir a Firefox";
    } else {
      this.element.href = this.chromeUrlValue;
      if (this.hasChromeTarget) this.chromeTarget.classList.remove('hidden');
      if (this.hasLabelTarget) this.labelTarget.textContent = "Añadir a Chrome";
    }
  }
}
