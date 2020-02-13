import { Component, OnInit, Input, HostListener } from "@angular/core";

declare var $: any;

@Component({
  selector: "app-header",
  templateUrl: "./header.component.html",
  styleUrls: ["./header.component.scss"]
})
export class HeaderComponent implements OnInit {
  @Input()
  public lightHeader = false;

  private loggedIn = false;

  @HostListener("window:scroll", ["$event"])
  onWindowScroll(e) {
    const element = document.querySelector(".navbar");

    if (window.pageYOffset > 5) {
      element.classList.add("sticky-header");
    } else {
      element.classList.remove("sticky-header");
    }
  }

  constructor() {}

  ngOnInit() {
    $(".navbar-toggler.collapsed").click(() => {
      $(".header").addClass("sticky-header");
    });
  }
}
