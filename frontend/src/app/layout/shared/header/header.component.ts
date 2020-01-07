import { Component, OnInit } from '@angular/core';
import { LoginService } from 'src/app/services/login.service';
import { Router } from '@angular/router';
import { NavigationService } from 'src/app/services/navigation.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html'
})
export class HeaderComponent implements OnInit {
  currentModule = 'content';

  constructor(
    private loginService: LoginService,
    private router: Router,
    private navigationService: NavigationService
  ) {
    this.currentModule = this.navigationService.currentModule;
  }
  ngOnInit() {}

  switchModule(module) {
    this.currentModule = module;
    this.navigationService.switchModule(module);
  }

  logOut() {
    this.loginService.logOut();
    this.router.navigate(['/login']);
  }
}
