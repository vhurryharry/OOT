import { Component, OnInit } from '@angular/core';
import { LoginService } from 'src/app/services/login.service';
import { Router } from '@angular/router';
import { NavigationService } from 'src/app/services/navigation.service';
import { RepositoryService } from 'src/app/services/repository.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {
  currentModule = 'content';
  menus = [];
  userName = '';
  isLoggedIn = false;

  constructor(
    private loginService: LoginService,
    private router: Router,
    private navigationService: NavigationService,
    private repository: RepositoryService
  ) {
    this.currentModule = this.navigationService.currentModule;

    this.isLoggedIn = this.loginService.isLoggedIn();
    if (this.isLoggedIn) {
      const user = this.loginService.getCurrentUser();
      this.userName = user.firstName + ' ' + user.lastName;
    }
  }

  ngOnInit() {
    this.repository.list('menu').subscribe((result: any) => {
      this.menus = result.items;
    });
  }

  switchModule(module) {
    this.currentModule = module;
    this.navigationService.switchModule(module);
  }

  logOut() {
    this.loginService.logOut();
    this.router.navigate(['/login']);
  }
}
