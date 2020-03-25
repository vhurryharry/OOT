import { Component, OnInit } from "@angular/core";
import * as ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { CourseService } from "../course.service";
import { Router } from "@angular/router";

@Component({
  selector: "app-new-course",
  templateUrl: "./new-course.component.html",
  styleUrls: ["./new-course.component.scss"]
})
export class NewCourseComponent implements OnInit {
  courseDetails = {
    title: "",
    address: "",
    tagline: "",
    city: "",
    latitude: "",
    longitude: "",
    startDate: null,
    lastDate: null,
    startTime: null,
    endTime: null,
    content: "",
    spots: 0,
    tuition: 0,
    instructors: [],
    note: ""
  };

  public editor = ClassicEditor;
  errorMsg: string = null;
  success = false;
  showError = false;

  loaded = false;
  instructors = [];
  selectedInstructors = [];

  constructor(private courseService: CourseService, private router: Router) {
    this.courseService.getInstructors().subscribe((result: any) => {
      this.instructors = result.instructors.map(instructor => {
        return {
          ...instructor,
          selected: false
        };
      });
      this.loaded = true;
    });
  }

  ngOnInit() {}

  selectInstructor(index) {
    this.instructors[index].selected = !this.instructors[index].selected;
  }

  selectSelectedInstructor(index) {
    this.selectedInstructors[index].selected = !this.selectedInstructors[index]
      .selected;
  }

  addToSelected() {
    for (let i = 0; i < this.instructors.length; i++) {
      if (this.instructors[i].selected) {
        this.selectedInstructors.push({
          ...this.instructors[i],
          selected: false
        });
        this.instructors.splice(i, 1);
        i--;
      }
    }
  }

  removeFromSelected() {
    for (let i = 0; i < this.selectedInstructors.length; i++) {
      if (this.selectedInstructors[i].selected) {
        this.instructors.push({
          ...this.selectedInstructors[i],
          selected: false
        });
        this.selectedInstructors.splice(i, 1);
        i--;
      }
    }
  }

  submitCourse() {
    for (const key in Object.keys(this.courseDetails)) {
      if (
        this.courseDetails[key] === null ||
        this.courseDetails[key] === "" ||
        this.courseDetails[key] === 0
      ) {
        this.showError = true;
        return;
      }
    }

    if (this.selectedInstructors.length === 0) {
      this.showError = true;
      return;
    }

    if (this.courseDetails.startDate <= new Date()) {
      this.errorMsg = "Start date can't be prior to today's date!";
      this.success = false;
      return;
    }

    if (
      this.courseDetails.startDate.getTime() >
      this.courseDetails.lastDate.getTime()
    ) {
      this.errorMsg = "Last date can't be prior to start date!";
      this.success = false;
      return;
    }

    if (
      this.courseDetails.startTime.getTime() >
      this.courseDetails.endTime.getTime()
    ) {
      this.errorMsg = "End time can't be prior to start time!";
      this.success = false;
      return;
    }

    const course = {
      ...this.courseDetails,
      slug: this.courseDetails.title
        .toLowerCase()
        .replace(/ /g, "-")
        .replace(/[^\w-]+/g, ""),
      location: `(${this.courseDetails.latitude}, ${this.courseDetails.longitude})`,
      instructors: this.selectedInstructors,
      status: "pending_confirmation",
      startTime: this.courseDetails.startTime.toLocaleTimeString(),
      endTime: this.courseDetails.endTime.toLocaleTimeString()
    };

    this.errorMsg = null;
    this.success = false;

    this.courseService.submitNewCourse(course).subscribe(
      (result: any) => {
        if (result.success) {
          this.success = true;
          this.router.navigateByUrl("/course");
        } else {
          this.success = false;
          this.errorMsg = result.error;
        }
      },
      error => {
        this.errorMsg = "Error occured!";
        this.success = false;
      }
    );
  }
}
