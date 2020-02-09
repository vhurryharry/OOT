import { Component, OnInit, ViewChild } from "@angular/core";

@Component({
  selector: "admin-condensed",
  templateUrl: "./condensed.component.html"
})
export class CondensedComponent implements OnInit {
  @ViewChild("root", { static: true }) root;

  constructor() {}

  ngOnInit() {}
}
