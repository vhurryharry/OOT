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
    $(".multi-item-carousel .carousel-item").each((index, item) => {
      let next = $(item).next();
      if (!next.length) {
        next = $(item).siblings(":first");
      }
      next
        .children(":first-child")
        .clone()
        .appendTo($(item));
    });
    $(".multi-item-carousel .carousel-item").each((index, item) => {
      let prev = $(item).prev();
      if (!prev.length) {
        prev = $(item).siblings(":last");
      }
      prev
        .children(":nth-last-child(2)")
        .clone()
        .prependTo($(item));
    });
  }
}
