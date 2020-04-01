import { Component, OnInit, ViewEncapsulation } from "@angular/core";
import { ActivatedRoute, Router } from "@angular/router";

import { PaymentService, ICartItem } from "src/app/services/payment.service";
import { CourseService } from "../course.service";
import { LoginService } from "src/app/services/login.service";

declare var $: any;

@Component({
  selector: "app-course-detail",
  templateUrl: "./course-detail.component.html",
  styleUrls: ["./course-detail.component.scss"],
  encapsulation: ViewEncapsulation.None
})
export class CourseDetailComponent implements OnInit {
  slug: string = null;
  dataLoaded = false;
  course: any;

  Math = Math;

  isInstructor = false;
  canEnroll = true;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private paymentService: PaymentService,
    private courseService: CourseService,
    private loginService: LoginService
  ) {
    this.isInstructor = this.loginService.isInstructor();

    this.route.params.subscribe(params => {
      this.slug = params.slug;
      let userId = "default";
      if (this.loginService.isLoggedIn()) {
        userId = this.loginService.getCurrentUserId();
      }

      const defaultAvatar = "/assets/images/images/auth/avatar.png";
      this.courseService
        .findBySlug(this.slug, userId)
        .subscribe((result: any) => {
          this.course = result.course;

          this.course.testimonials = this.course.testimonials.map(item => {
            if (!item.author_avatar) {
              item.author_avatar = defaultAvatar;
            }

            return item;
          });

          this.course.location = JSON.parse(
            "[" + this.course.location.slice(1, -1) + "]"
          );

          const temp = this.course.location[0];
          this.course.location[0] = parseFloat(this.course.location[1]);
          this.course.location[1] = parseFloat(temp);

          this.course.location[0] =
            this.course.location[0] > 90
              ? 90
              : this.course.location[0] < -90
              ? -90
              : this.course.location[0];
          this.course.location[1] =
            this.course.location[1] > 90
              ? 90
              : this.course.location[1] < -90
              ? -90
              : this.course.location[1];

          this.dataLoaded = true;

          this.course.startTime = this.timeConvert(this.course.startTime);
          this.course.endTime = this.timeConvert(this.course.endTime);

          if (
            !this.course.price ||
            this.course.reserved_count >= this.course.spots ||
            this.course.reserved ||
            new Date(this.course.startDate) <= new Date()
          ) {
            this.canEnroll = false;
          }

          setTimeout(() => {
            $("[data-toggle='tooltip']").tooltip();

            this.initCarousels("course-testimonials-gallery");
          }, 500);
        });
    });
  }

  timeConvert(time) {
    // Check correct time format and split into components
    time = time.match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

    if (time.length > 1) {
      // If time format correct
      time = time.slice(1); // Remove full string match value
      time[3] = +time[0] < 12 ? " AM" : " PM"; // Set AM/PM
      time[4] = "";
      time[0] = +time[0] % 12 || 12; // Adjust hours
    }
    return time.join(""); // return adjusted time or original string
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
        price: this.course.price,
        quantity: 1
      };

      this.paymentService.addToCart(item);
      this.router.navigateByUrl("/cart");
    } else {
      this.router.navigateByUrl("/signup");
    }
  }
}
