import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class RepositoryService {
  constructor(private http: HttpClient) { }

  fetch(module, state) {
    return this.http.post('/admin/api/' + module + '/search', {state});
  }

  find(module, id) {
    return this.http.post('/admin/api/' + module + '/find', {id});
  }

  create(module, data) {
    return this.http.post('/admin/api/' + module + '/create', data);
  }

  update(module, data) {
    return this.http.post('/admin/api/' + module + '/update', data);
  }

  delete(module, ids) {
    return this.http.post('/admin/api/' + module + '/delete', {ids});
  }

  restore(module, ids) {
    return this.http.post('/admin/api/' + module + '/restore', {ids});
  }

  export(module, ids) {
    return this.http.post('/admin/api/' + module + '/export', {ids});
  }

  move(module, id, type) {
    return this.http.post('/admin/api/' + module + '/move', {type, id});
  }

  attach(type, payload) {
    return this.http.post('/admin/api/attach/' + type, payload);
  }

  detach(type, payload) {
    return this.http.post('/admin/api/detach/' + type, payload);
  }

  getPayment(paymentId) {
    return this.http.post('/admin/api/order/' + paymentId + '/payment', {});
  }

  refund(paymentId) {
    return this.http.post('/admin/api/order/' + paymentId + '/refund', {});
  }
}
