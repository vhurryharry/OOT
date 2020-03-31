import { Component, OnInit } from "@angular/core";
import { CourseService } from "../course.service";

@Component({
  selector: "app-course",
  templateUrl: "./courses.component.html",
  styleUrls: ["./courses.component.scss"]
})
export class CoursesComponent implements OnInit {
  courses: any = [];
  categories: any = [
    {
      id: 0,
      category: "All"
    }
  ];

  selectedCategory = 0;
  dataLoaded = false;

  constructor(private courseService: CourseService) {}

  ngOnInit() {
    this.courseService.get().subscribe((result: any) => {
      this.courses = result.courses.map(course => {
        course.canEnroll = course.spots > course.reservations;

        course.statusStr = course.canEnroll
          ? "Registration Open"
          : course.spots <= course.reservations
          ? "Sold Out"
          : "Registration Open";

        if (new Date(course.start_date) <= new Date()) {
          course.statusStr = "Active";
          course.canEnroll = false;
        }

        if (new Date(course.last_date) < new Date()) {
          course.statusStr = "Completed";
          course.canEnroll = false;
        }

        return course;
      });

      this.categories = this.categories.concat(
        result.categories.sort((a, b) => a.id - b.id)
      );
      this.dataLoaded = true;
    });
  }

  onSelectCategory(newCategory) {
    this.selectedCategory = newCategory;
    console.log(this.selectedCategory);

    this.courses.forEach((course, i) => {
      if (this.selectedCategory === 0) {
        this.courses[i].hidden = false;
      } else {
        if (
          this.courses[i].categories.findIndex(
            category => category.id === this.selectedCategory
          ) < 0
        ) {
          this.courses[i].hidden = true;
        } else {
          this.courses[i].hidden = false;
        }
      }
    });
  }
}
