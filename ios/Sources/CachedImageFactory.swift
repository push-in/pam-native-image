import Foundation
import PamNative
import SDWebImage
import UIKit

public final class CachedImageFactory: NativeViewFactory, @unchecked Sendable {
    public init() {}
    public func create(context: AnyObject?, emit: @escaping (Data) -> Void) -> UIView { CachedImageView(emit: emit) }
    public func update(view: UIView, properties: [String: WireValue]) { (view as? CachedImageView)?.update(properties) }
    public func release(view: UIView) { (view as? UIImageView)?.sd_cancelCurrentImageLoad(); (view as? UIImageView)?.image = nil }
}

private final class CachedImageView: UIImageView, @unchecked Sendable {
    private let emit: (Data) -> Void
    private var source = ""
    init(emit: @escaping (Data) -> Void) { self.emit = emit; super.init(frame: .zero); clipsToBounds = true }
    required init?(coder: NSCoder) { nil }
    func update(_ values: [String: WireValue]) {
        contentMode = switch values.integer("contentMode", 1) { case 2: .scaleAspectFill; case 3: .scaleToFill; case 4: .center; default: .scaleAspectFit }
        let next = values.text("source"); guard next != source else { return }; source = next
        let crossfade = max(0, min(1_000, values.integer("crossfadeMillis", 120)))
        sd_imageTransition = crossfade == 0 ? nil : .fade(duration: Double(crossfade) / 1_000)
        guard let url = resolve(next) else { send(["event": .integer(2), "message": .text("Invalid image source")]); return }
        let policy = values.integer("cachePolicy", 1)
        let cacheType: SDImageCacheType = switch policy { case 2: .memory; case 3: .disk; case 4: .none; default: .all }
        var options: SDWebImageOptions = [.retryFailed, .scaleDownLargeImages]
        if policy == 4 { options.insert(.fromLoaderOnly) }
        let context: [SDWebImageContextOption: Any] = [.queryCacheType: cacheType.rawValue, .storeCacheType: cacheType.rawValue]
        sd_setImage(with: url, placeholderImage: nil, options: options, context: context, progress: nil, completed: { [weak self] image, error, _, _ in
            guard let self else { return }
            if let image { self.send(["event": .integer(1), "width": .integer(Int64(image.size.width)), "height": .integer(Int64(image.size.height))]) }
            else { self.send(["event": .integer(2), "message": .text(error?.localizedDescription ?? "Image load failed")]) }
        })
    }
    private func resolve(_ value: String) -> URL? { if value.hasPrefix("https://") { return URL(string: value) }; let root = FileManager.default.urls(for: .applicationSupportDirectory, in: .userDomainMask)[0].standardizedFileURL; let target = root.appendingPathComponent(value).standardizedFileURL; return target.path.hasPrefix(root.path + "/") ? target : nil }
    private func send(_ values: [String: WireValue]) { if let data = try? WireMap.encode(values) { emit(data) } }
}
private extension Dictionary where Key == String, Value == WireValue { func text(_ key: String) -> String { if case let .text(value)? = self[key] { return value }; return "" }; func integer(_ key: String, _ fallback: Int64) -> Int64 { if case let .integer(value)? = self[key] { return value }; return fallback } }
