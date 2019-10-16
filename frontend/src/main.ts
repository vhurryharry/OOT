import { enableProdMode } from '@angular/core';
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';
import { AppModule } from './app/app.module';
import { environment } from './environments/environment';

if (environment.production) {
  enableProdMode();
}

if (document.getElementById('app')) {
  platformBrowserDynamic().bootstrapModule(AppModule).catch(err => console.error(err));
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.navbar-burger').forEach(function (element: any) {
    element.addEventListener('click', function () {
      var target = document.getElementById(element.dataset.target);
      element.classList.toggle('is-active');
      target.classList.toggle('is-active');
    });
  });

  document.querySelectorAll('.is-toggler').forEach(function (element: any) {
    element.addEventListener('click', function () {
      var target = document.getElementById(element.dataset.target);
      target.classList.toggle('is-active');
    });
  });
});
