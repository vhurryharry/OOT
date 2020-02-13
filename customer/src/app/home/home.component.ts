import { Component, OnInit } from "@angular/core";

declare var $: any;

@Component({
  selector: "app-homepage",
  templateUrl: "./home.component.html",
  styleUrls: ["./home.component.scss"]
})
export class HomeComponent implements OnInit {
  constructor() {}

  ngOnInit() {
    // Image Gallery configuration
    $(".multi-item-carousel .carousel-item").each(() => {
      let next = $(this).next();
      if (!next.length) {
        next = $(this).siblings(":first");
      }
      next
        .children(":first-child")
        .clone()
        .appendTo($(this));
    });
    $(".multi-item-carousel .carousel-item").each(() => {
      let prev = $(this).prev();
      if (!prev.length) {
        prev = $(this).siblings(":last");
      }
      prev
        .children(":nth-last-child(2)")
        .clone()
        .prependTo($(this));
    });
  }
}
