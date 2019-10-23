import { enableProdMode } from '@angular/core';
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';
import { CheckoutModule } from './checkout/checkout.module';
import { environment } from './environments/environment';

if (environment.production) {
  enableProdMode();
}

if (document.getElementById('checkout')) {
  platformBrowserDynamic().bootstrapModule(CheckoutModule).catch(err => console.error(err));
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.navbar-burger').forEach((element: any) => {
    element.addEventListener('click', () => {
      const target = document.getElementById(element.dataset.target);
      element.classList.toggle('is-active');
      target.classList.toggle('is-active');
    });
  });

  document.querySelectorAll('.is-toggler').forEach((element: any) => {
    element.addEventListener('click', () => {
      const target = document.getElementById(element.dataset.target);
      target.classList.toggle('is-active');
    });
  });
});
