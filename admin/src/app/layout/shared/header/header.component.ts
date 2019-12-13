import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'admin-header',
  templateUrl: './header.component.html'
})
export class HeaderComponent implements OnInit {
  currentModule = 'content';

  constructor() {}
  ngOnInit() {}

  switchModule(module) {
    this.currentModule = module;
  }
}
