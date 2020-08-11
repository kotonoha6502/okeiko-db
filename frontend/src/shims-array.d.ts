declare interface Array<T> {
  flatMap: <U, This = undefined>(
    callback: (value: T, index: number, array: T[]) => U | readonly U[],
    thisArg?: This | undefined
  ) => U[]
}
