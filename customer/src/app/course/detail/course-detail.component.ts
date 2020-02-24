import { Component, OnInit } from "@angular/core";
import { CourseService } from "../course.service";
import { ActivatedRoute } from "@angular/router";

declare var $: any;

@Component({
  selector: "app-course-detail",
  templateUrl: "./course-detail.component.html",
  styleUrls: ["./course-detail.component.scss"]
})
export class CourseDetailComponent implements OnInit {
  slug: string = null;
  dataLoaded = false;
  course: any;

  Math = Math;

  constructor(
    private route: ActivatedRoute,
    private courseService: CourseService
  ) {
    this.route.params.subscribe(params => {
      this.slug = params.slug;
      this.courseService.findBySlug(this.slug).subscribe((result: any) => {
        this.course = result.course;

        let i = 0;
        let total = 0;
        for (; i < this.course.reviews.length; i++) {
          total += this.course.reviews[i].rating;
        }

        this.course.rating = total / i;

        this.course.location = JSON.parse(
          "[" + this.course.location.slice(1, -1) + "]"
        );

        this.dataLoaded = true;

        setTimeout(() => {
          $('[data-toggle="tooltip"]').tooltip();

          this.initCarousels("course-testimonials-gallery");
        }, 500);
      });
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

  ngOnInit() {}
}
