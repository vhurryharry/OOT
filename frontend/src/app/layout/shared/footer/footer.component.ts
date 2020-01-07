import { Component, OnInit } from '@angular/core';
import { NavigationService } from 'src/app/services/navigation.service';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html'
})
export class FooterComponent implements OnInit {
  currentModule = 'content';

  constructor(private navigationService: NavigationService) {
    this.currentModule = this.navigationService.currentModule;
  }
  ngOnInit() {}
}
