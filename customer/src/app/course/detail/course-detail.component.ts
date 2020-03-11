import { Component, OnInit } from "@angular/core";
import { ActivatedRoute, Router } from "@angular/router";

import { PaymentService, ICartItem } from "src/app/services/payment.service";
import { CourseService } from "../course.service";
import { LoginService } from "src/app/services/login.service";

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
    private router: Router,
    private paymentService: PaymentService,
    private courseService: CourseService,
    private loginService: LoginService
  ) {
    this.route.params.subscribe(params => {
      this.slug = params.slug;
      let userId = "default";
      if (this.loginService.isLoggedIn()) {
        userId = this.loginService.getCurrentUserId();
      }

      this.courseService
        .findBySlug(this.slug, userId)
        .subscribe((result: any) => {
          this.course = result.course;

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

  initCarousels(id) {
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

  enroll() {
    if (this.loginService.isLoggedIn()) {
      const item: ICartItem = {
        id: this.course.id,
        name: this.course.title,
        price: this.course.options[0].price,
        quantity: 1
      };

      this.paymentService.addToCart(item);
      this.router.navigateByUrl("/cart");
    } else {
      this.router.navigateByUrl("/signup");
    }
  }
}
