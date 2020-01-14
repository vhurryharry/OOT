import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { EntityService } from 'src/app/services/entity.service';

@Component({
  selector: 'app-condensed',
  templateUrl: './condensed.component.html'
})
export class CondensedComponent implements OnInit {
  @ViewChild('root', { static: true }) root;

  private content = [];

  constructor(private router: Router, private entityService: EntityService) {}

  ngOnInit() {
    const slug = this.router.url.substr(1);
    this.entityService.entity(slug).subscribe((result: any) => {
      this.content = result.entity.content;
    });
    console.log(this.router.url);
  }
}
