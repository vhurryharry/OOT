import { Component, OnInit, ViewChild } from "@angular/core";
import { Router, NavigationEnd } from "@angular/router";

@Component({
  selector: "app-condensed",
  templateUrl: "./condensed.component.html"
})
export class CondensedComponent implements OnInit {
  @ViewChild("root", { static: false }) root;

  constructor(private router: Router) {}

  ngOnInit() {
    this.router.events.subscribe(evt => {
      if (!(evt instanceof NavigationEnd)) {
        return;
      }
      window.scrollTo(0, 0);
    });
  }
}
