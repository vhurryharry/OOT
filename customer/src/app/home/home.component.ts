import { Component, OnInit } from "@angular/core";
import { HomeService } from "./home.service";

declare var $: any;

@Component({
  selector: "app-homepage",
  templateUrl: "./home.component.html",
  styleUrls: ["./home.component.scss"]
})
export class HomeComponent implements OnInit {
  testimonials: any;

  constructor(private homeService: HomeService) {
    homeService.getTestimonials().subscribe((result: any) => {
      this.testimonials = result.testimonials;

      setTimeout(() => {
        this.initCarousels("home-testimonials-gallery");
      }, 500);
    });
  }

  initCarousels = id => {
    // Image Gallery configuration
    $("#" + id + ".multi-item-carousel .carousel-item").each((index, item) => {
      let next = $(item).next();
      if (!next.length) {
        next = $(item).siblings(":first");
      }
      next
        .children(":first-child")
        .clone()
        .appendTo($(item));
    });
    $("#" + id + ".multi-item-carousel .carousel-item").each((index, item) => {
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

  ngOnInit() {
    this.initCarousels("home-gallery");
  }
}
