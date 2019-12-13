import { Component, OnInit } from '@angular/core';
import { LoginService } from 'src/app/services/login.service';
import { Router } from '@angular/router';

@Component({
  selector: 'admin-header',
  templateUrl: './header.component.html'
})
export class HeaderComponent implements OnInit {
  currentModule = 'content';

  constructor(private loginService: LoginService, private router: Router) {}
  ngOnInit() {}

  switchModule(module) {
    this.currentModule = module;
  }

  logOut() {
    this.loginService.logOut();
    this.router.navigate(['/login']);
  }
}
