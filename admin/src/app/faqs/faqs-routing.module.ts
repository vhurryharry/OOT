import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { FaqsComponent } from './faqs.component';
import { FaqListComponent } from './faq-list/faq-list.component';
import { CreateFaqComponent } from './create-faq/create-faq.component';

const routes: Routes = [
  {
    path: '',
    component: FaqsComponent,
    children: [
      {
        path: 'edit/:id',
        component: CreateFaqComponent
      },
      {
        path: '',
        component: FaqListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class FaqsRoutingModule {}
